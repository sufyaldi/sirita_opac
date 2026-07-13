@extends('layouts.admin')

@section('title', 'Kelola Denda Sirkulasi - SIRITA Admin')
@section('header_title', 'Kelola Denda Anggota')

@section('content')
<style>
    .fines-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .fines-container {
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

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-form {
        display: flex;
        gap: 0.5rem;
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

    .badge-paid {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
    }

    .badge-unpaid {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
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

<div class="fines-container">
    <!-- Manual Fine Add Card -->
    <div class="glass-card">
        <div class="card-title">Tambah Denda Manual</div>
        <form action="{{ route('admin.circulation.fines.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="member_id" class="form-label">ID Anggota / Barcode Anggota</label>
                <input type="text" id="member_id" name="member_id" class="form-control" placeholder="M00001" required>
            </div>
            <div class="form-group">
                <label for="debet" class="form-label">Nominal Denda (Rp)</label>
                <input type="number" id="debet" name="debet" class="form-control" placeholder="5000" min="0" required>
            </div>
            <div class="form-group">
                <label for="description" class="form-label">Keterangan Denda</label>
                <input type="text" id="description" name="description" class="form-control" placeholder="Buku rusak / hilang / terlambat..." required>
            </div>
            <button type="submit" class="btn-primary">Tambah Denda</button>
        </form>
    </div>

    <!-- Fines list Card -->
    <div class="glass-card">
        <div class="toolbar">
            <div class="card-title" style="margin: 0;">Daftar Catatan Denda</div>
            <form action="{{ route('admin.circulation.fines') }}" method="GET" class="search-form">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="padding: 0.5rem 1rem;" placeholder="Cari nama/ID anggota...">
                <button type="submit" class="btn-primary" style="width: auto; padding: 0.5rem 1rem;">Cari</button>
            </form>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Tgl Denda</th>
                        <th>Anggota</th>
                        <th>Keterangan</th>
                        <th>Denda (Debet)</th>
                        <th>Bayar (Credit)</th>
                        <th>Status</th>
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fines as $fine)
                        <tr>
                            <td><code>{{ $fine->fines_date->toDateString() }}</code></td>
                            <td>
                                <strong>{{ $fine->member->member_name ?? '-' }}</strong><br>
                                <span style="font-size: 0.85rem; color: #94a3b8;">{{ $fine->member_id }}</span>
                            </td>
                            <td>{{ $fine->description }}</td>
                            <td>Rp {{ number_format($fine->debet, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($fine->credit, 0, ',', '.') }}</td>
                            <td>
                                @if($fine->credit >= $fine->debet)
                                    <span class="badge badge-paid">Lunas</span>
                                @else
                                    <span class="badge badge-unpaid">Belum Lunas</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                    @if($fine->credit < $fine->debet)
                                        <form action="{{ route('admin.circulation.fines.pay', $fine->fines_id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #10b981;">Lunas</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.circulation.fines.destroy', $fine->fines_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan denda ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #ef4444;">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada catatan denda saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $fines->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
