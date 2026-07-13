@extends('layouts.admin')

@section('title', 'Terbitan Berseri - SIRITA Admin')
@section('header_title', 'Kontrol Terbitan Berseri (Serials Control)')

@section('content')
<style>
    .serial-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .serial-grid {
            grid-template-columns: 1fr;
        }
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

<div class="serial-grid">
    <!-- Add Subscription Form Card -->
    <div class="glass-card">
        <div class="card-title">Mulai Langganan Baru</div>
        <form action="{{ route('admin.serial-control.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title" class="form-label">Judul Jurnal / Majalah</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Harvard Business Review" required>
            </div>
            <div class="form-group">
                <label for="publisher_name" class="form-label">Penerbit</label>
                <input type="text" id="publisher_name" name="publisher_name" class="form-control" placeholder="Contoh: Harvard Business Publishing">
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="period_start" class="form-label">Mulai</label>
                    <input type="date" id="period_start" name="period_start" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="period_end" class="form-label">Selesai</label>
                    <input type="date" id="period_end" name="period_end" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label for="frequency" class="form-label">Frekuensi Terbit</label>
                <select id="frequency" name="frequency" class="form-control" style="background-color: #1e293b;">
                    <option value="Mingguan">Mingguan</option>
                    <option value="Dwi-Mingguan">Dwi-Mingguan</option>
                    <option value="Bulanan" selected>Bulanan</option>
                    <option value="Dwi-Bulanan">Dwi-Bulanan</option>
                    <option value="Tri-Wulan">Tri-Wulan</option>
                    <option value="Tahunan">Tahunan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notes" class="form-label">Catatan Tambahan</label>
                <input type="text" id="notes" name="notes" class="form-control" placeholder="No. rak penyimpanan, dsb...">
            </div>

            <button type="submit" class="btn-primary">Daftarkan Langganan</button>
        </form>
    </div>

    <!-- Subscriptions List Card -->
    <div class="glass-card">
        <div class="card-title">Daftar Langganan Aktif</div>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul Terbitan</th>
                        <th>Penerbit</th>
                        <th>Frekuensi</th>
                        <th>Periode Kontrak</th>
                        <th style="width: 220px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                        <tr>
                            <td><strong>{{ $sub->title }}</strong></td>
                            <td>{{ $sub->publisher_name ?? '-' }}</td>
                            <td>{{ $sub->frequency }}</td>
                            <td>
                                <code>{{ $sub->period_start->toDateString() }}</code> s.d <code>{{ $sub->period_end->toDateString() }}</code>
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    <a href="{{ route('admin.serial-control.kardex', $sub->subscription_id) }}" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; height: auto;">Kartu Kardex</a>
                                    
                                    <form action="{{ route('admin.serial-control.destroy', $sub->subscription_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus langganan ini beserta seluruh kartu kardex-nya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #ef4444;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada langganan terbitan berseri terdaftar saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $subscriptions->links() }}
        </div>
    </div>
</div>
@endsection
