@extends('layouts.admin')

@section('title', 'Keanggotaan - SIRITA Admin')
@section('header_title', 'Manajemen Keanggotaan')

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
        <form action="{{ route('admin.membership.index') }}" method="GET" class="search-form">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari nama / ID...">
            <button type="submit" class="btn-primary">Cari</button>
        </form>
        <a href="{{ route('admin.membership.create') }}" class="btn-primary">+ Tambah Anggota</a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Email</th>
                    <th>Masa Berlaku</th>
                    <th style="width: 160px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr>
                        <td><code>{{ $member->member_id }}</code></td>
                        <td><strong>{{ $member->member_name }}</strong></td>
                        <td>{{ $member->member_email ?? '-' }}</td>
                        <td>{{ $member->expire_date->toDateString() }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <a href="{{ route('admin.membership.edit', $member->member_id) }}" class="btn-action" style="color: #60a5fa;">Ubah</a>
                                <form action="{{ route('admin.membership.destroy', $member->member_id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action" style="color: #f87171; background: transparent; cursor: pointer;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada data anggota saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        {{ $members->appends(request()->query())->links() }}
    </div>
</div>
@endsection
