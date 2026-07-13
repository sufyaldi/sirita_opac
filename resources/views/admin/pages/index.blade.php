@extends('layouts.admin')

@section('title', 'Kelola Halaman OPAC - SIRITA Admin')
@section('header_title', 'Kelola Halaman Dinamis OPAC')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2.5rem;
        border-radius: 20px;
        backdrop-filter: blur(12px);
    }

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.6rem 1.25rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .table-responsive {
        overflow-x: auto;
        margin-top: 1.5rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    th {
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    td {
        color: #f1f5f9;
        font-size: 0.95rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-edit {
        background: transparent;
        border: 1px solid #eab308;
        color: #facc15;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
        text-decoration: none;
        margin-right: 0.5rem;
    }

    .btn-edit:hover {
        background: rgba(234, 179, 8, 0.1);
    }

    .btn-delete {
        background: transparent;
        border: 1px solid #f87171;
        color: #f87171;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background: rgba(248, 113, 113, 0.1);
    }
</style>

<div class="glass-card">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; color: #cbd5e1;">Halaman Penjelas OPAC</h3>
        <a href="{{ route('admin.pages.create') }}" class="btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Halaman
        </a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Judul Halaman</th>
                    <th>URL Slug</th>
                    <th>Link OPAC</th>
                    <th>Status</th>
                    <th style="text-align: right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pages as $page)
                    <tr>
                        <td style="font-weight: 600;">{{ $page->title }}</td>
                        <td style="font-family: monospace; color: #94a3b8;">{{ $page->slug }}</td>
                        <td>
                            <a href="{{ url('/page/' . $page->slug) }}" target="_blank" style="color: #38bdf8; font-family: monospace; text-decoration: none;">
                                /page/{{ $page->slug }}
                            </a>
                        </td>
                        <td>
                            @if($page->is_active === 1)
                                <span class="badge badge-active">Aktif / Terbit</span>
                            @else
                                <span class="badge badge-inactive">Draft / Simpan</span>
                            @endif
                        </td>
                        <td style="text-align: right; display: flex; justify-content: flex-end; align-items: center;">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn-edit">Edit</a>
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus halaman ini?')" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
