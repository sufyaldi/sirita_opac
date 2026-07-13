@extends('layouts.admin')

@section('title', 'Pengembalian Kilat - SIRITA Admin')
@section('header_title', 'Pengembalian Kilat')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
    }

    .form-inline {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .form-control {
        padding: 0.8rem 1rem;
        border-radius: 10px;
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid var(--border-color);
        color: white;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .btn-secondary {
        background: transparent;
        border: 1px solid var(--border-color);
        color: #94a3b8;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
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
</style>

<div class="glass-card">
    <div style="margin-bottom: 1.5rem; color: #94a3b8;">
        Gunakan pemindai barcode atau ketik kode eksemplar buku secara manual untuk langsung mengembalikan koleksi tanpa harus membuka profil anggota.
    </div>

    <form action="{{ route('admin.circulation.quick-return.post') }}" method="POST" class="form-inline">
        @csrf
        <input type="text" name="item_code" class="form-control" style="flex: 1; min-width: 250px;" placeholder="Masukkan Kode Barcode Eksemplar..." required autofocus>
        <button type="submit" class="btn-primary">Kembalikan Buku</button>
        <a href="{{ route('admin.circulation.index') }}" class="btn-secondary">Kembali</a>
    </form>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3rem; border-top: 1px solid var(--border-color); padding-top: 2rem;">
        <h3 style="color: white; margin: 0;">Log Pengembalian Hari Ini (Sesi Ini)</h3>
        @if(!empty($log))
            <form action="{{ route('admin.circulation.quick-return.clear') }}" method="POST" style="margin: 0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-secondary" style="color: #f87171; border-color: rgba(239, 68, 68, 0.2);">Bersihkan Log</button>
            </form>
        @endif
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Waktu Kembali</th>
                    <th>Kode Eksemplar</th>
                    <th>ID Anggota</th>
                    <th>Status / Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($log as $entry)
                    <tr>
                        <td><code>{{ $entry['return_time'] }}</code></td>
                        <td><code>{{ $entry['item_code'] }}</code></td>
                        <td><code>{{ $entry['member_id'] }}</code></td>
                        <td>
                            @if(str_contains($entry['status'], 'Denda') || str_contains($entry['status'], 'Terlambat'))
                                <span style="color: #f87171;">{{ $entry['status'] }}</span>
                            @else
                                <span style="color: #34d399;">{{ $entry['status'] }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada pengembalian yang dilakukan dalam sesi ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
