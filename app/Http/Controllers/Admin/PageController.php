<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    /**
     * Display a listing of the pages.
     */
    public function index()
    {
        $pages = Page::orderByDesc('id')->get();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new page.
     */
    public function create()
    {
        return view('admin.pages.create');
    }

    /**
     * Store a newly created page in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:200',
            'content'   => 'required|string',
            'is_active' => 'required|integer|in:0,1',
        ]);

        $validated['slug'] = Str::slug($request->title);

        // Pastikan slug unik
        $count = Page::where('slug', 'like', $validated['slug'] . '%')->count();
        if ($count > 0) {
            $validated['slug'] .= '-' . ($count + 1);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
                         ->with('success', 'Halaman dinamis berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified page.
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Update the specified page in database.
     */
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title'     => 'required|string|max:200',
            'content'   => 'required|string',
            'is_active' => 'required|integer|in:0,1',
        ]);

        // Regenerate slug if title changes
        if ($page->title !== $request->title) {
            $validated['slug'] = Str::slug($request->title);
            $count = Page::where('slug', 'like', $validated['slug'] . '%')->where('id', '!=', $id)->count();
            if ($count > 0) {
                $validated['slug'] .= '-' . ($count + 1);
            }
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')
                         ->with('success', 'Halaman dinamis berhasil diperbarui.');
    }

    /**
     * Remove the specified page from database.
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect()->route('admin.pages.index')
                         ->with('success', 'Halaman dinamis berhasil dihapus.');
    }
}
