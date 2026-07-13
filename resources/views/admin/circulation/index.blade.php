@extends('layouts.admin')

@section('title', 'Sirkulasi - SIRITA Admin')
@section('header_title', 'Layanan Sirkulasi')

@section('content')
<style>
    .circulation-dashboard {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .circulation-dashboard {
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
        margin-bottom: 1.5rem;
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
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .table-container {
        overflow-x: auto;
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

<div class="circulation-dashboard">
    <!-- Transaction Start Card -->
    <div class="glass-card">
        <div class="card-title">Mulai Transaksi Sirkulasi</div>
        <form action="{{ route('admin.circulation.start') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="member_id" class="form-label">ID Anggota / Barcode Anggota</label>
                <input type="text" id="member_id" name="member_id" class="form-control" placeholder="M00001" required autofocus>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="submit" class="btn-primary">Mulai Transaksi</button>
                <a href="{{ route('admin.circulation.quick-return') }}" class="btn-primary" style="background: transparent; border: 1px solid var(--border-color); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; height: 44px; box-sizing: border-box;">Pengembalian Kilat</a>
            </div>
        </form>
    </div>

    <!-- Recent Loans Card -->
    <div class="glass-card">
        <div class="card-title">Transaksi Terkini</div>
        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Anggota</th>
                        <th>Kode Eksemplar</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans as $loan)
                        <tr>
                            <td>{{ $loan->member->member_name }}</td>
                            <td><code>{{ $loan->item_code }}</code></td>
                            <td>{{ $loan->loan_date->toDateString() }}</td>
                            <td>
                                @if($loan->is_return)
                                    <span style="color: #34d399;">Kembali</span>
                                @else
                                    <span style="color: #f87171;">Dipinjam</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b;">Belum ada transaksi saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
