<?php

/**
 * SIRITA SLiMS - Sistem Informasi Riset & Tata Arsip
 * Laravel Edition — Port of SLiMS 9 Bulian
 *
 * @author    Sufyaldy <sufyaldys@gmail.com>
 * @license   AGPL-3.0-or-later
 * @copyright 2026 Sufyaldy
 */

namespace App\Http\Controllers;

use App\Models\Biblio;
use Illuminate\Http\Request;

class OpacController extends Controller
{
    /**
     * Display the OPAC home page and search results.
     */
    public function index(Request $request)
    {
        $query = Biblio::with(['authors', 'gmd'])->where('opac_hide', 0);

        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('isbn_issn', 'like', '%' . $search . '%')
                  ->orWhere('series_title', 'like', '%' . $search . '%')
                  ->orWhere('classification', 'like', '%' . $search . '%');
            });
        }

        // Paginate results
        $results = $query->paginate(12);

        // Hitung statistik perpustakaan real-time
        $totalTitles = Biblio::where('opac_hide', 0)->count();
        $totalItems  = \App\Models\Item::count();
        $activeLoans = \App\Models\Loan::where('is_return', 0)->count();

        // Ambil pengumuman/berita yang aktif
        $promotions = \App\Models\Promotion::active()->get();

        return view('opac.index', compact('results', 'totalTitles', 'totalItems', 'activeLoans', 'promotions'));
    }

    /**
     * Show detailed bibliography information.
     */
    public function show($id)
    {
        $book = Biblio::with(['authors', 'publisher', 'gmd', 'items'])->where('opac_hide', 0)->findOrFail($id);
        
        return view('opac.show', compact('book'));
    }
}
