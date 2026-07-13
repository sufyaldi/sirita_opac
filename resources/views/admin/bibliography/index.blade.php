@extends('layouts.admin')

@section('title', 'Bibliografi - SIRITA Admin')
@section('header_title', 'Daftar Katalog Bibliografi')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
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

<div class="glass-card">
    <div class="toolbar">
        <form action="{{ route('admin.bibliography.index') }}" method="GET" class="search-form">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari judul / ISBN...">
            <button type="submit" class="btn-primary">Cari</button>
        </form>
        <a href="{{ route('admin.bibliography.create') }}" class="btn-primary">+ Tambah Bibliografi</a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>ISBN / ISSN</th>
                    <th>Penerbit</th>
                    <th>Tahun</th>
                    <th style="width: 160px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bibliographies as $book)
                    <tr>
                        <td>
                            <strong>{{ $book->title }}</strong>
                        </td>
                        <td><code>{{ $book->isbn_issn ?? '-' }}</code></td>
                        <td>{{ $book->publisher->publisher_name ?? '-' }}</td>
                        <td>{{ $book->publish_year ?? '-' }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('admin.bibliography.edit', $book->biblio_id) }}" class="btn-action" style="color: #60a5fa;">Ubah</a>
                                <form action="{{ route('admin.bibliography.destroy', $book->biblio_id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus katalog ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action" style="color: #f87171; background: transparent; cursor: pointer;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada katalog bibliografi saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $bibliographies->appends(request()->query())->links() }}
    </div>
</div>
@endsection
