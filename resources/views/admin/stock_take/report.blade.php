@extends('layouts.admin')

@section('title', 'Laporan Stok Opname: ' . $session->session_name . ' - SIRITA Admin')
@section('header_title', 'Laporan Sesi Stok Opname')

@section('content')
<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .metric-val {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0.5rem 0;
    }

    .metric-label {
        font-size: 0.9rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
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

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }
</style>

<!-- Header & Back Button -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 style="color: white; margin: 0; font-size: 1.5rem;">Sesi: {{ $session->session_name }}</h2>
    <a href="{{ route('admin.stock-take.current') }}" class="btn-primary">&larr; Kembali ke Panel Scan</a>
</div>

<!-- Metric summary -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-label">Total Koleksi di Database</div>
        <div class="metric-val">{{ $totalItems }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Ditemukan (Verified)</div>
        <div class="metric-val" style="color: #34d399;">{{ $totalVerified }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Sedang Dipinjam (Lent)</div>
        <div class="metric-val" style="color: #6366f1;">{{ $totalLent }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Belum Ditemukan (Missing)</div>
        <div class="metric-val" style="color: #f87171;">{{ $totalMissing }}</div>
    </div>
</div>

<!-- Missing items list -->
<div class="glass-card">
    <div class="card-title">Daftar Buku Belum Ditemukan (Diduga Hilang)</div>
    <p style="color: #94a3b8; font-size: 0.9rem; margin-top: -1rem; margin-bottom: 1.5rem;">
        Berikut adalah daftar eksemplar buku yang tidak ditemukan di rak (belum dipindai) dan tidak sedang dipinjam oleh anggota.
    </p>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Kode Eksemplar</th>
                    <th>Judul Buku</th>
                    <th>No. Panggil</th>
                </tr>
            </thead>
            <tbody>
                @forelse($missingItems as $item)
                    <tr>
                        <td><code>{{ $item->item_code }}</code></td>
                        <td><strong>{{ $item->biblio->title ?? 'Buku' }}</strong></td>
                        <td>{{ $item->call_number ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #34d399; padding: 2rem 0; font-weight: 500;">
                            Luar biasa! Semua eksemplar fisik buku telah berhasil diverifikasi lengkap.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 1.5rem;">
        {{ $missingItems->links() }}
    </div>
</div>
@endsection
