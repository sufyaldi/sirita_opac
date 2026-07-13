<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of custom OPAC menus.
     */
    public function index()
    {
        $menus = Menu::orderBy('sort_order')->get();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'url'        => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active'  => 'required|integer|in:0,1',
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menus.index')
                         ->with('success', 'Menu OPAC berhasil ditambahkan.');
    }

    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'url'        => 'required|string|max:255',
            'sort_order' => 'required|integer|min:0',
            'is_active'  => 'required|integer|in:0,1',
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')
                         ->with('success', 'Menu OPAC berhasil diperbarui.');
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menus.index')
                         ->with('success', 'Menu OPAC berhasil dihapus.');
    }
}
PHPEOF
