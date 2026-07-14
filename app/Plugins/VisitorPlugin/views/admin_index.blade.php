@extends('layouts.admin')

@section('title', 'Laporan Kunjungan Perpustakaan - SIRITA Admin')
@section('header_title', 'Statistik & Laporan Kunjungan')

@section('content')
<style>
    .filter-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
        margin-bottom: 2rem;
    }
    .filter-form {
        display: flex;
        gap: 1rem;
        align-items: center;
    }
    .filter-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        background: rgba(15, 23, 42, 0.6);
        color: white;
        font-size: 0.9rem;
    }
    .filter-input:focus {
        outline: none;
        border-color: #818cf8;
    }
    .btn-action {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .btn-search {
        background: #818cf8;
        color: white;
    }
    .btn-search:hover {
        background: #6366f1;
    }
    .btn-reset {
        background: rgba(255,255,255,0.08);
        color: #cbd5e1;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-reset:hover {
        background: rgba(255,255,255,0.15);
    }
    .btn-delete {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.3);
        color: white;
    }
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }
    .admin-table th, .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .admin-table th {
        color: #94a3b8;
        font-weight: 500;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .admin-table td {
        color: #cbd5e1;
        font-size: 0.9rem;
    }
    .admin-table tr:hover td {
        background: rgba(255,255,255,0.02);
    }
    .badge {
        display: inline-block;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-member {
        background: rgba(13,148,136,0.15);
        color: #2dd4bf;
    }
    .badge-guest {
        background: rgba(99,102,241,0.15);
        color: #a5b4fc;
    }
    .pagination-wrapper {
        margin-top: 1.5rem;
    }
</style>

@if(session('success'))
    <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500;">
        ✓ {{ session('success') }}
    </div>
@endif

<!-- Search Filter -->
<div class="filter-card">
    <form action="{{ route('admin.visitor.index') }}" method="GET" class="filter-form">
        <input type="text" name="search" class="filter-input" placeholder="Cari nama, ID anggota, institusi, atau tipe pengunjung..." value="{{ request('search') }}">
        <button type="submit" class="btn-action btn-search">Cari Data</button>
        @if(request('search'))
            <a href="{{ route('admin.visitor.index') }}" class="btn-action btn-reset">Reset</a>
        @endif
    </form>
</div>

<!-- Table Card -->
<div class="table-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="color: white; margin: 0; font-size: 1.1rem; font-weight: 600;">Daftar Kunjungan Hari Ini & Log Riwayat</h3>
    </div>

    @if($visitors->isEmpty())
        <p style="color: #64748b; text-align: center; padding: 3rem 0; margin: 0;">Tidak ada log data kunjungan perpustakaan.</p>
    @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>ID Anggota</th>
                    <th>Nama Lengkap</th>
                    <th>Asal Institusi</th>
                    <th>Jenis</th>
                    <th>Waktu Kunjungan</th>
                    <th style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($visitors as $index => $v)
                    <tr>
                        <td>{{ $visitors->firstItem() + $index }}</td>
                        <td style="font-family: monospace; font-weight: bold; color: #818cf8;">{{ $v->member_id ?? '-' }}</td>
                        <td style="font-weight: 600; color: white;">{{ $v->name }}</td>
                        <td>{{ $v->institution ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $v->member_id ? 'badge-member' : 'badge-guest' }}">
                                {{ $v->visitor_type }}
                            </span>
                        </td>
                        <td>{{ $v->created_at->translatedFormat('d M Y - H:i') }} ({{ $v->created_at->diffForHumans() }})</td>
                        <td>
                            <form action="{{ route('admin.visitor.destroy', $v->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log kunjungan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $visitors->appends(request()->input())->links() }}
        </div>
    @endif
</div>
@endsection
