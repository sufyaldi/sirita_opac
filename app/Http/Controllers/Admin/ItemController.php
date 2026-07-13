<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a listing of items.
     */
    public function index(Request $request)
    {
        $query = DB::table('item')
            ->leftJoin('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
            ->leftJoin('mst_coll_type', 'item.coll_type_id', '=', 'mst_coll_type.coll_type_id')
            ->leftJoin('mst_location', 'item.location_id', '=', 'mst_location.location_id')
            ->leftJoin('mst_item_status', 'item.item_status_id', '=', 'mst_item_status.item_status_id')
            ->select(
                'item.*', 
                'biblio.title as biblio_title', 
                'mst_coll_type.coll_type_name', 
                'mst_location.location_name',
                'mst_item_status.item_status_name'
            );

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('item.item_code', 'like', '%' . $search . '%')
                  ->orWhere('biblio.title', 'like', '%' . $search . '%');
        }

        $items = $query->paginate(20);

        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit($id)
    {
        $item = Item::with('biblio')->findOrFail($id);

        $collTypes = DB::table('mst_coll_type')->orderBy('coll_type_name')->get();
        $locations = DB::table('mst_location')->orderBy('location_name')->get();
        $statuses  = DB::table('mst_item_status')->orderBy('item_status_name')->get();

        return view('admin.items.edit', compact('item', 'collTypes', 'locations', 'statuses'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'coll_type_id' => 'required|integer',
            'location_id' => 'required|integer',
            'item_status_id' => 'required|string|max:3',
            'call_number' => 'nullable|string|max:50',
            'inventory_code' => 'nullable|string|max:50',
            'source' => 'required|integer',
            'price' => 'nullable|integer',
            'received_date' => 'nullable|date',
        ]);

        $item->update(array_merge($validated, [
            'last_update' => now(),
        ]));

        return redirect()->route('admin.items.index')
                         ->with('success', 'Eksemplar buku berhasil diperbarui.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.items.index')
                         ->with('success', 'Eksemplar buku berhasil dihapus.');
    }
}
