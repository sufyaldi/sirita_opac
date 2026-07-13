@extends('layouts.admin')

@section('title', 'Sesi Aktif: ' . $session->session_name . ' - SIRITA Admin')
@section('header_title', 'Stok Opname Berjalan')

@section('content')
<style>
    .current-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 2rem;
    }

    @media (max-width: 900px) {
        .current-layout {
            grid-template-columns: 1fr;
        }
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
        box-sizing: border-box;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: white;
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .info-label {
        color: #94a3b8;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: white;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
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
        width: 100%;
        text-align: center;
        box-sizing: border-box;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .btn-danger {
        background: #ef4444;
        border: none;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
        text-align: center;
        box-sizing: border-box;
    }

    .btn-danger:hover {
        background: #dc2626;
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
</style>

<div class="current-layout">
    <!-- Session details & actions -->
    <div class="glass-card">
        <div class="card-title">Detail Sesi</div>
        <ul class="info-list">
            <li>
                <span class="info-label">Nama Sesi</span>
                <span class="info-value">{{ $session->session_name }}</span>
            </li>
            <li>
                <span class="info-label">Tanggal Mulai</span>
                <span class="info-value"><code>{{ $session->created_at->toDateString() }}</code></span>
            </li>
        </ul>

        <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
            <a href="{{ route('admin.stock-take.report') }}" class="btn-primary">Lihat Laporan Sesi</a>
            
            <form action="{{ route('admin.stock-take.finish') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan sesi stok opname ini? Semua eksemplar yang tidak ditemukan akan otomatis ditandai sebagai \'Lost\'.')">
                @csrf
                <button type="submit" class="btn-danger">Selesaikan Sesi</button>
            </form>
        </div>
    </div>

    <!-- Scanner & Scanned Logs -->
    <div class="scan-area" style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Scanner Input Card -->
        <div class="glass-card">
            <div class="card-title" style="margin-bottom: 1rem;">Verifikasi Buku (Scan Barcode)</div>
            <form action="{{ route('admin.stock-take.scan') }}" method="POST">
                @csrf
                <div style="display: flex; gap: 1rem;">
                    <input type="text" name="item_code" class="form-control" placeholder="Pindai barcode atau masukkan kode eksemplar buku (Contoh: B00001)..." required autofocus>
                    <button type="submit" class="btn-primary" style="width: auto; padding: 0.8rem 2rem;">Verifikasi</button>
                </div>
            </form>
        </div>

        <!-- Scanned logs list -->
        <div class="glass-card">
            <div class="card-title">Eksemplar Baru Saja Diverifikasi</div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Waktu Pindai</th>
                            <th>Kode Eksemplar</th>
                            <th>Judul Buku</th>
                            <th>No. Panggil</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td><code>{{ $log->created_at->toTimeString() }}</code></td>
                                <td><code>{{ $log->item_code }}</code></td>
                                <td><strong>{{ $log->item->biblio->title ?? 'Buku' }}</strong></td>
                                <td>{{ $log->item->call_number ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada buku yang dipindai pada sesi ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem;">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
