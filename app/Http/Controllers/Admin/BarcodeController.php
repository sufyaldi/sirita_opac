<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display the list of items available for barcode and label printing.
     */
    public function index(Request $request)
    {
        $query = Item::with('biblio');

        if ($request->has('search')) {
            $query->where('item_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('biblio', function($q) use ($request) {
                      $q->where('title', 'like', '%' . $request->search . '%');
                  });
        }

        $items = $query->paginate(15);

        return view('admin.barcode.index', compact('items'));
    }

    /**
     * Print barcodes for selected items.
     */
    public function printBarcodes(Request $request)
    {
        $request->validate([
            'item_codes' => 'required|array',
        ]);

        $items = Item::with('biblio')
            ->whereIn('item_code', $request->item_codes)
            ->get();

        return view('admin.barcode.print', compact('items'));
    }

    /**
     * Print labels for selected items.
     */
    public function printLabels(Request $request)
    {
        $request->validate([
            'item_codes' => 'required|array',
        ]);

        $items = Item::with(['biblio.publisher', 'biblio.authors'])
            ->whereIn('item_code', $request->item_codes)
            ->get();

        return view('admin.barcode.label', compact('items'));
    }
}
