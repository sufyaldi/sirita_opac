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
use App\Services\MarcService;
use Illuminate\Http\Request;

class MarcController extends Controller
{
    protected $marcService;

    public function __construct(MarcService $marcService)
    {
        $this->marcService = $marcService;
    }

    /**
     * Display MARC Import page.
     */
    public function showImport()
    {
        return view('admin.marc.import');
    }

    /**
     * Process uploaded MARCXML file and save records.
     */
    public function doImport(Request $request)
    {
        $request->validate([
            'marc_file' => 'required|file|mimes:xml',
        ]);

        $fileContent = file_get_contents($request->file('marc_file')->getRealPath());
        $books = $this->marcService->parseMarcXml($fileContent);

        if (empty($books)) {
            return redirect()->back()->with('error', 'Gagal memparsing berkas MARCXML atau rekam data kosong.');
        }

        $importedCount = 0;

        foreach ($books as $book) {
            // Find or Create Publisher
            $publisherId = null;
            if (!empty($book['publisher'])) {
                $publisher = Publisher::firstOrCreate(
                    ['publisher_name' => $book['publisher']],
                    ['input_date' => now()->toDateString()]
                );
                $publisherId = $publisher->publisher_id;
            }

            // Find or Create Place of Publication
            $placeId = null;
            if (!empty($book['publish_place'])) {
                $place = Place::firstOrCreate(
                    ['place_name' => $book['publish_place']],
                    ['input_date' => now()->toDateString()]
                );
                $placeId = $place->place_id;
            }

            // Create Bibliography record
            $biblio = Biblio::create([
                'title' => $book['title'],
                'isbn_issn' => $book['isbn'],
                'publisher_id' => $publisherId,
                'publish_place_id' => $placeId,
                'publish_year' => $book['publish_year'],
                'edition' => $book['edition'],
                'classification' => $book['classification'],
                'input_date' => now(),
                'last_update' => now(),
            ]);

            // Find or Create Author & Associate
            if (!empty($book['author'])) {
                $author = Author::firstOrCreate(
                    ['author_name' => $book['author']],
                    [
                        'authority_type' => 'p',
                        'input_date' => now()->toDateString(),
                    ]
                );

                $biblio->authors()->attach($author->author_id, ['level' => 1]);
            }

            $importedCount++;
        }

        return redirect()->route('admin.bibliography.index')
                         ->with('success', $importedCount . ' buku berhasil diimpor dari berkas MARCXML.');
    }

    /**
     * Display MARC Export page.
     */
    public function showExport(Request $request)
    {
        $query = Biblio::with(['publisher', 'authors']);

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn_issn', 'like', '%' . $request->search . '%');
        }

        $biblios = $query->paginate(15);

        return view('admin.marc.export', compact('biblios'));
    }

    /**
     * Download selected books as a MARCXML file.
     */
    public function doExport(Request $request)
    {
        $request->validate([
            'biblio_ids' => 'required|array',
        ]);

        $books = Biblio::with(['publisher', 'publishPlace', 'authors'])
            ->whereIn('biblio_id', $request->biblio_ids)
            ->get();

        if ($books->isEmpty()) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu buku untuk diekspor.');
        }

        $xmlString = $this->marcService->generateMarcXml($books);
        
        $filename = 'MARCXML_Export_' . date('Y-m-d_H-i-s') . '.xml';

        return response($xmlString, 200, [
            'Content-Type' => 'application/xml',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
