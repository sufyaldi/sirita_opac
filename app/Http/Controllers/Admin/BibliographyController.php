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
use App\Models\Biblio;
use Illuminate\Http\Request;

class BibliographyController extends Controller
{
    /**
     * Display a listing of bibliographies.
     */
    public function index(Request $request)
    {
        $query = Biblio::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn_issn', 'like', '%' . $request->search . '%');
        }

        $bibliographies = $query->paginate(15);

        // For this migration, we return a view. Depending on the approved plan,
        // it can be Blade or Inertia response. We'll use standard Laravel view.
        return view('admin.bibliography.index', compact('bibliographies'));
    }    /**
     * Show the form for creating a new bibliography.
     */
    public function create()
    {
        $gmds       = \App\Models\Gmd::orderBy('gmd_name')->get();
        $publishers = \App\Models\Publisher::orderBy('publisher_name')->get();
        $places     = \App\Models\Place::orderBy('place_name')->get();
        $languages  = \App\Models\Language::orderBy('language_name')->get();
        $authors    = \App\Models\Author::orderBy('author_name')->get();
        $topics     = \App\Models\Topic::orderBy('topic')->get();

        return view('admin.bibliography.create', compact('gmds', 'publishers', 'places', 'languages', 'authors', 'topics'));
    }

    /**
     * Store a newly created bibliography in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn_issn' => 'nullable|string|max:32',
            'publish_year' => 'nullable|string|max:20',
            'edition' => 'nullable|string|max:50',
            'call_number' => 'nullable|string|max:50',
            'classification' => 'nullable|string|max:40',
            'gmd_id' => 'nullable|integer',
            'publisher_id' => 'nullable|integer',
            'publish_place_id' => 'nullable|integer',
            'language_id' => 'nullable|integer',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'attachment' => 'nullable|file|max:10240', // Limit 10MB
            'authors' => 'nullable|array',
            'topics' => 'nullable|array',
        ]);

        $data = $validated;
        unset($data['cover_image'], $data['attachment'], $data['authors'], $data['topics']);

        // Handle Cover Image
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/covers', $filename);
            $data['image'] = 'covers/' . $filename;
        }

        $bibliography = Biblio::create(array_merge($data, [
            'input_date' => now(),
            'last_update' => now(),
        ]));

        // Sync many-to-many
        if ($request->has('authors')) {
            $bibliography->authors()->sync($request->input('authors'));
        }
        if ($request->has('topics')) {
            $bibliography->topics()->sync($request->input('topics'));
        }

        // Handle Attachment File
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/attachments', $filename);

            $attachment = \App\Models\AttachmentFile::create([
                'file_title' => $file->getClientOriginalName(),
                'file_name' => $filename,
                'file_dir' => 'attachments/' . $filename,
                'mime_type' => $file->getMimeType(),
                'input_date' => now(),
                'last_update' => now(),
            ]);

            $bibliography->attachments()->attach($attachment->file_id, [
                'placement' => 'link',
                'access_type' => 'public',
            ]);
        }

        return redirect()->route('admin.bibliography.index')
                         ->with('success', 'Bibliografi berhasil ditambahkan.');
    }

    /**
     * Display the specified bibliography.
     */
    public function show($id)
    {
        $bibliography = Biblio::with(['attachments','orgUnits', 'authors', 'topics'])->findOrFail($id);
        $allUnits     = \App\Models\OrgUnit::active()->orderBy('sort_order')->get();
        return view('admin.bibliography.show', compact('bibliography', 'allUnits'));
    }

    /**
     * Show the form for editing the specified bibliography.
     */
    public function edit($id)
    {
        $bibliography   = Biblio::with(['attachments','orgUnits', 'authors', 'topics'])->findOrFail($id);
        $allUnits       = \App\Models\OrgUnit::active()->orderBy('sort_order')->get();
        $taggedUnitIds  = $bibliography->orgUnits->pluck('id')->toArray();

        $gmds       = \App\Models\Gmd::orderBy('gmd_name')->get();
        $publishers = \App\Models\Publisher::orderBy('publisher_name')->get();
        $places     = \App\Models\Place::orderBy('place_name')->get();
        $languages  = \App\Models\Language::orderBy('language_name')->get();
        $authors    = \App\Models\Author::orderBy('author_name')->get();
        $topics     = \App\Models\Topic::orderBy('topic')->get();

        $selectedAuthors = $bibliography->authors->pluck('author_id')->toArray();
        $selectedTopics  = $bibliography->topics->pluck('topic_id')->toArray();

        return view('admin.bibliography.edit', compact(
            'bibliography', 'allUnits', 'taggedUnitIds', 'gmds', 'publishers', 'places', 
            'languages', 'authors', 'topics', 'selectedAuthors', 'selectedTopics'
        ));
    }

    /**
     * Update the specified bibliography in storage.
     */
    public function update(Request $request, $id)
    {
        $bibliography = Biblio::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn_issn' => 'nullable|string|max:32',
            'publish_year' => 'nullable|string|max:20',
            'edition' => 'nullable|string|max:50',
            'call_number' => 'nullable|string|max:50',
            'classification' => 'nullable|string|max:40',
            'gmd_id' => 'nullable|integer',
            'publisher_id' => 'nullable|integer',
            'publish_place_id' => 'nullable|integer',
            'language_id' => 'nullable|integer',
            'notes' => 'nullable|string',
            'cover_image' => 'nullable|image|max:2048',
            'attachment' => 'nullable|file|max:10240',
            'authors' => 'nullable|array',
            'topics' => 'nullable|array',
        ]);

        $data = $validated;
        unset($data['cover_image'], $data['attachment'], $data['authors'], $data['topics']);

        // Handle Cover Image Update
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/covers', $filename);
            $data['image'] = 'covers/' . $filename;
        }

        $bibliography->update(array_merge($data, [
            'last_update' => now(),
        ]));

        // Sync many-to-many
        $bibliography->authors()->sync($request->input('authors', []));
        $bibliography->topics()->sync($request->input('topics', []));

        // Handle Attachment File Update
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/attachments', $filename);

            $attachment = \App\Models\AttachmentFile::create([
                'file_title' => $file->getClientOriginalName(),
                'file_name' => $filename,
                'file_dir' => 'attachments/' . $filename,
                'mime_type' => $file->getMimeType(),
                'input_date' => now(),
                'last_update' => now(),
            ]);

            // Sync or attach new attachment
            $bibliography->attachments()->attach($attachment->file_id, [
                'placement' => 'link',
                'access_type' => 'public',
            ]);
        }

        return redirect()->route('admin.bibliography.index')
                         ->with('success', 'Bibliografi berhasil diperbarui.');
    }

    /**
     * Remove the specified bibliography from storage.
     */
    public function destroy($id)
    {
        $bibliography = Biblio::findOrFail($id);
        $bibliography->delete();

        return redirect()->route('admin.bibliography.index')
                         ->with('success', 'Bibliografi berhasil dihapus.');
    }
}
