@extends('layouts.admin')

@section('title', 'Kartu Kardex: ' . $subscription->title . ' - SIRITA Admin')
@section('header_title', 'Kartu Kendali Kardex')

@section('content')
<style>
    .kardex-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 2rem;
    }

    @media (max-width: 900px) {
        .kardex-layout {
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
    }

    .btn-primary:hover {
        background: var(--primary-hover);
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

    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-expected {
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
    }

    .badge-received {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
    }

    .badge-missing {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }
</style>

<div class="kardex-layout">
    <!-- Subscription Details Sidebar -->
    <div class="glass-card">
        <div class="card-title">Profil Langganan</div>
        <ul class="info-list">
            <li>
                <span class="info-label">Judul Publikasi</span>
                <span class="info-value">{{ $subscription->title }}</span>
            </li>
            <li>
                <span class="info-label">Penerbit</span>
                <span class="info-value">{{ $subscription->publisher_name ?? '-' }}</span>
            </li>
            <li>
                <span class="info-label">Frekuensi Terbit</span>
                <span class="info-value">{{ $subscription->frequency }}</span>
            </li>
            <li>
                <span class="info-label">Durasi Kontrak</span>
                <span class="info-value">
                    {{ $subscription->period_start->toDateString() }} s.d {{ $subscription->period_end->toDateString() }}
                </span>
            </li>
        </ul>
        
        <!-- Add Expected Issue Form -->
        <div style="margin-top: 2.5rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
            <div class="card-title" style="font-size: 1.1rem; margin-bottom: 1rem;">Edisi yang Diharapkan</div>
            <form action="{{ route('admin.serial-control.kardex.store', $subscription->subscription_id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="issue_no" class="form-label">No. Volume / Edisi</label>
                    <input type="text" id="issue_no" name="issue_no" class="form-control" placeholder="Contoh: Vol. 15 No. 4" required>
                </div>
                <div class="form-group">
                    <label for="publish_date" class="form-label">Estimasi Tgl Terbit</label>
                    <input type="date" id="publish_date" name="publish_date" class="form-control" required>
                </div>
                <button type="submit" class="btn-primary">Daftarkan Edisi</button>
            </form>
        </div>

        <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem; text-align: center;">
            <a href="{{ route('admin.serial-control.index') }}" style="color: #94a3b8; text-decoration: none; font-weight: 500;">&larr; Kembali ke Daftar</a>
        </div>
    </div>

    <!-- Kardex Card Records -->
    <div class="glass-card">
        <div class="card-title">Kartu Kontrol Kedatangan Edisi</div>
        
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>No. Volume / Edisi</th>
                        <th>Estimasi Terbit</th>
                        <th>Tgl Penerimaan</th>
                        <th>Status</th>
                        <th style="width: 200px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kardexes as $kardex)
                        <tr>
                            <td><strong>{{ $kardex->issue_no }}</strong></td>
                            <td><code>{{ $kardex->publish_date ? $kardex->publish_date->toDateString() : '-' }}</code></td>
                            <td><code>{{ $kardex->receive_date ? $kardex->receive_date->toDateString() : '-' }}</code></td>
                            <td>
                                @if($kardex->status === 'received')
                                    <span class="badge badge-received">Diterima</span>
                                @elseif($kardex->status === 'missing')
                                    <span class="badge badge-missing">Hilang</span>
                                @else
                                    <span class="badge badge-expected">Expected</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    @if($kardex->status !== 'received')
                                        <form action="{{ route('admin.serial-control.kardex.receive', $kardex->kardex_id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #10b981;">Terima</button>
                                        </form>
                                    @endif
                                    
                                    @if($kardex->status !== 'missing')
                                        <form action="{{ route('admin.serial-control.kardex.missing', $kardex->kardex_id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #f59e0b;">Hilang</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.serial-control.kardex.destroy', $kardex->kardex_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus edisi ini dari rekam Kardex?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #ef4444;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada edisi terdaftar pada kartu Kardex langganan ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
