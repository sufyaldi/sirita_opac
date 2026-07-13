<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Biblio;
use App\Models\Place;
use App\Models\Publisher;
use App\Services\SruService;
use Illuminate\Http\Request;

class CopyCatalogingController extends Controller
{
    protected $sruService;

    public function __construct(SruService $sruService)
    {
        $this->sruService = $sruService;
    }

    /**
     * Display SRU Copy Cataloging form and search results.
     */
    public function index(Request $request)
    {
        $query = $request->input('search');
        $server = $request->input('server', 'http://z3950.loc.gov:7090/voyager?');
        $results = [];

        if (!empty($query)) {
            $results = $this->sruService->search($query, $server);
            
            // Store results in session temporarily so they can be imported by index/id
            session(['sru_results' => $results]);
        }

        return view('admin.copy_cataloging.index', compact('results', 'query', 'server'));
    }

    /**
     * Import a catalog record into local database.
     */
    public function import(Request $request)
    {
        $request->validate([
            'record_id' => 'required|integer',
        ]);

        $results = session('sru_results', []);
        $recordId = $request->record_id;

        if (!isset($results[$recordId])) {
            return redirect()->back()->with('error', 'Rekam data katalog tidak ditemukan.');
        }

        $record = $results[$recordId];

        // 1. Find or Create Publisher
        $publisherId = null;
        if (!empty($record['publisher'])) {
            $publisher = Publisher::firstOrCreate(
                ['publisher_name' => $record['publisher']],
                ['input_date' => now()->toDateString()]
            );
            $publisherId = $publisher->publisher_id;
        }

        // 2. Find or Create Place of Publication
        $placeId = null;
        if (!empty($record['publish_place'])) {
            $place = Place::firstOrCreate(
                ['place_name' => $record['publish_place']],
                ['input_date' => now()->toDateString()]
            );
            $placeId = $place->place_id;
        }

        // 3. Create Bibliography (Biblio)
        $biblio = Biblio::create([
            'title' => $record['title'],
            'isbn_issn' => $record['isbn'],
            'publisher_id' => $publisherId,
            'publish_place_id' => $placeId,
            'publish_year' => $record['publish_year'],
            'edition' => $record['edition'],
            'classification' => $record['classification'],
            'input_date' => now(),
            'last_update' => now(),
        ]);

        // 4. Find or Create Author & Associate
        if (!empty($record['author'])) {
            $author = Author::firstOrCreate(
                ['author_name' => $record['author']],
                [
                    'authority_type' => 'p',
                    'input_date' => now()->toDateString(),
                ]
            );

            $biblio->authors()->attach($author->author_id, ['level' => 1]);
        }

        return redirect()->route('admin.bibliography.index')
                         ->with('success', 'Buku "' . $biblio->title . '" berhasil di-copy cataloging dari server luar.');
    }
}
