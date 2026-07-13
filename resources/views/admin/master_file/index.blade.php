@extends('layouts.admin')

@section('title', 'Master Data - SIRITA Admin')
@section('header_title', 'Manajemen Master Data')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
    }

    .tab-navigation {
        display: flex;
        gap: 0.5rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
        scrollbar-width: thin;
    }

    .tab-item {
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        color: #94a3b8;
        text-decoration: none;
        font-weight: 500;
        white-space: nowrap;
        transition: all 0.3s;
    }

    .tab-item:hover, .tab-item.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .search-form {
        display: flex;
        gap: 0.5rem;
    }

    .form-control {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid var(--border-color);
        color: white;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        border: 1px solid var(--border-color);
        color: #cfd8dc;
        transition: all 0.3s;
    }

    .btn-action:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th, .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .admin-table th {
        color: #94a3b8;
        font-weight: 500;
    }

    .admin-table td {
        color: white;
    }

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .pagination a, .pagination span {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: white;
        text-decoration: none;
    }

    .pagination .active {
        background: var(--primary);
        border-color: var(--primary);
    }
</style>

<!-- Horizontal Dynamic Tabs Navigation -->
<div class="tab-navigation">
    <a href="{{ route('admin.master-file.index', 'author') }}" class="tab-item {{ $type == 'author' ? 'active' : '' }}">Pengarang</a>
    <a href="{{ route('admin.master-file.index', 'publisher') }}" class="tab-item {{ $type == 'publisher' ? 'active' : '' }}">Penerbit</a>
    <a href="{{ route('admin.master-file.index', 'gmd') }}" class="tab-item {{ $type == 'gmd' ? 'active' : '' }}">GMD</a>
    <a href="{{ route('admin.master-file.index', 'location') }}" class="tab-item {{ $type == 'location' ? 'active' : '' }}">Lokasi Rak</a>
    <a href="{{ route('admin.master-file.index', 'place') }}" class="tab-item {{ $type == 'place' ? 'active' : '' }}">Tempat Terbit</a>
    <a href="{{ route('admin.master-file.index', 'coll-type') }}" class="tab-item {{ $type == 'coll-type' ? 'active' : '' }}">Tipe Koleksi</a>
    <a href="{{ route('admin.master-file.index', 'item-status') }}" class="tab-item {{ $type == 'item-status' ? 'active' : '' }}">Status Eksemplar</a>
    <a href="{{ route('admin.master-file.index', 'language') }}" class="tab-item {{ $type == 'language' ? 'active' : '' }}">Bahasa</a>
    <a href="{{ route('admin.master-file.index', 'topic') }}" class="tab-item {{ $type == 'topic' ? 'active' : '' }}">Subjek / Topik</a>
    <a href="{{ route('admin.master-file.index', 'member-type') }}" class="tab-item {{ $type == 'member-type' ? 'active' : '' }}">Tipe Anggota</a>
</div>

<div class="glass-card">
    <div class="toolbar">
        <form action="{{ route('admin.master-file.index', $type) }}" method="GET" class="search-form">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari {{ $info['label'] }}...">
            <button type="submit" class="btn-primary">Cari</button>
        </form>
        <a href="{{ route('admin.master-file.create', $type) }}" class="btn-primary">+ Tambah {{ $info['name'] }}</a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID / Kode</th>
                    <th>{{ $info['label'] }}</th>
                    @if(isset($info['extra_fields']))
                        @foreach($info['extra_fields'] as $key => $label)
                            <th>{{ $label }}</th>
                        @endforeach
                    @endif
                    <th style="width: 160px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $pk = $info['pk'];
                    $field = $info['field'];
                @endphp
                @forelse($records as $record)
                    <tr>
                        <td><code>{{ $record->$pk }}</code></td>
                        <td><strong>{{ $record->$field }}</strong></td>
                        @if(isset($info['extra_fields']))
                            @foreach($info['extra_fields'] as $key => $label)
                                <td>{{ $record->$key }}</td>
                            @endforeach
                        @endif
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('admin.master-file.edit', ['type' => $type, 'id' => $record->$pk]) }}" class="btn-action" style="color: #60a5fa;">Ubah</a>
                                <form action="{{ route('admin.master-file.destroy', ['type' => $type, 'id' => $record->$pk]) }}" method="POST" style="margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action" style="color: #f87171; background: transparent; cursor: pointer;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 3 + (isset($info['extra_fields']) ? count($info['extra_fields']) : 0) }}" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada data saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $records->appends(request()->query())->links() }}
    </div>
</div>
@endsection
