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
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MasterFileController extends Controller
{
    // Mapping model keys to their namespaces and primary keys
    protected $types = [
        'author' => [
            'model' => \App\Models\Author::class,
            'name' => 'Pengarang',
            'pk' => 'author_id',
            'field' => 'author_name',
            'label' => 'Nama Pengarang'
        ],
        'publisher' => [
            'model' => \App\Models\Publisher::class,
            'name' => 'Penerbit',
            'pk' => 'publisher_id',
            'field' => 'publisher_name',
            'label' => 'Nama Penerbit'
        ],
        'gmd' => [
            'model' => \App\Models\Gmd::class,
            'name' => 'GMD (General Material Designation)',
            'pk' => 'gmd_id',
            'field' => 'gmd_name',
            'label' => 'Nama GMD',
            'extra_fields' => ['gmd_code' => 'Kode GMD']
        ],
        'location' => [
            'model' => \App\Models\Location::class,
            'name' => 'Lokasi Rak',
            'pk' => 'location_id',
            'field' => 'location_name',
            'label' => 'Nama Lokasi',
            'manual_pk' => true,
            'pk_label' => 'ID Lokasi (3 Karakter)'
        ],
        'place' => [
            'model' => \App\Models\Place::class,
            'name' => 'Tempat Terbit',
            'pk' => 'place_id',
            'field' => 'place_name',
            'label' => 'Nama Kota/Tempat'
        ],
        'coll-type' => [
            'model' => \App\Models\CollType::class,
            'name' => 'Tipe Koleksi',
            'pk' => 'coll_type_id',
            'field' => 'coll_type_name',
            'label' => 'Nama Tipe Koleksi'
        ],
        'item-status' => [
            'model' => \App\Models\ItemStatus::class,
            'name' => 'Status Eksemplar',
            'pk' => 'item_status_id',
            'field' => 'item_status_name',
            'label' => 'Nama Status',
            'manual_pk' => true,
            'pk_label' => 'ID Status (3 Karakter)'
        ],
        'language' => [
            'model' => \App\Models\Language::class,
            'name' => 'Bahasa Dokumen',
            'pk' => 'language_id',
            'field' => 'language_name',
            'label' => 'Nama Bahasa',
            'manual_pk' => true,
            'pk_label' => 'ID Bahasa (2-5 Karakter)'
        ],
        'topic' => [
            'model' => \App\Models\Topic::class,
            'name' => 'Subjek / Topik',
            'pk' => 'topic_id',
            'field' => 'topic',
            'label' => 'Nama Topik',
            'extra_fields' => [
                'topic_type' => 'Tipe Topik (t/g/n/tm/gr/oc)',
                'classification' => 'Kode Klasifikasi'
            ]
        ],
        'member-type' => [
            'model' => \App\Models\MemberType::class,
            'name' => 'Tipe Anggota',
            'pk' => 'member_type_id',
            'field' => 'member_type_name',
            'label' => 'Nama Tipe Anggota',
            'extra_fields' => [
                'loan_limit' => 'Batas Pinjam (Buku)',
                'loan_periode' => 'Durasi Pinjam (Hari)',
                'fine_each_day' => 'Denda Harian (Rp)',
                'member_periode' => 'Masa Aktif Anggota (Hari)'
            ]
        ]
    ];

    protected function getInfo($type)
    {
        if (!isset($this->types[$type])) {
            abort(404, 'Tipe master data tidak valid.');
        }
        return $this->types[$type];
    }

    /**
     * Display list of master data.
     */
    public function index($type, Request $request)
    {
        $info = $this->getInfo($type);
        $modelClass = $info['model'];
        $query = $modelClass::query();

        if ($request->has('search')) {
            $query->where($info['field'], 'like', '%' . $request->search . '%');
        }

        $records = $query->paginate(15);

        return view('admin.master_file.index', compact('records', 'info', 'type'));
    }

    /**
     * Show form to create new master data.
     */
    public function create($type)
    {
        $info = $this->getInfo($type);
        return view('admin.master_file.create', compact('info', 'type'));
    }

    /**
     * Store new master data.
     */
    public function store($type, Request $request)
    {
        $info = $this->getInfo($type);
        $modelClass = $info['model'];

        $rules = [];
        if (isset($info['manual_pk'])) {
            $rules[$info['pk']] = 'required|string|unique:' . (new $modelClass)->getTable() . ',' . $info['pk'];
        }
        $rules[$info['field']] = 'required|string|max:100';

        if (isset($info['extra_fields'])) {
            foreach ($info['extra_fields'] as $key => $lbl) {
                $rules[$key] = 'required|string|max:100';
            }
        }

        $validated = $request->validate($rules);

        // Add default dates if columns exist
        $data = array_merge($validated, [
            'input_date' => now()->toDateString(),
            'last_update' => now()->toDateString(),
        ]);

        $modelClass::create($data);

        return redirect()->route('admin.master-file.index', $type)
                         ->with('success', 'Data ' . $info['name'] . ' baru berhasil ditambahkan.');
    }

    /**
     * Show form to edit master data.
     */
    public function edit($type, $id)
    {
        $info = $this->getInfo($type);
        $modelClass = $info['model'];
        $record = $modelClass::where($info['pk'], $id)->firstOrFail();

        return view('admin.master_file.edit', compact('record', 'info', 'type'));
    }

    /**
     * Update master data.
     */
    public function update($type, $id, Request $request)
    {
        $info = $this->getInfo($type);
        $modelClass = $info['model'];
        $record = $modelClass::where($info['pk'], $id)->firstOrFail();

        $rules = [
            $info['field'] => 'required|string|max:100'
        ];

        if (isset($info['extra_fields'])) {
            foreach ($info['extra_fields'] as $key => $lbl) {
                $rules[$key] = 'required|string|max:100';
            }
        }

        $validated = $request->validate($rules);

        $record->update(array_merge($validated, [
            'last_update' => now()->toDateString(),
        ]));

        return redirect()->route('admin.master-file.index', $type)
                         ->with('success', 'Data ' . $info['name'] . ' berhasil diperbarui.');
    }

    /**
     * Delete master data.
     */
    public function destroy($type, $id)
    {
        $info = $this->getInfo($type);
        $modelClass = $info['model'];
        $record = $modelClass::where($info['pk'], $id)->firstOrFail();
        $record->delete();

        return redirect()->route('admin.master-file.index', $type)
                         ->with('success', 'Data ' . $info['name'] . ' berhasil dihapus.');
    }
}
