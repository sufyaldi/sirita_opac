<?php

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

        return view('opac.index', compact('results'));
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
