@extends('layouts.admin')

@section('title', 'Transaksi: ' . $member->member_name . ' - SIRITA Admin')
@section('header_title', 'Transaksi Sirkulasi')

@section('content')
<style>
    .transaction-layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 2rem;
    }

    @media (max-width: 900px) {
        .transaction-layout {
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

    .checkout-section {
        margin-bottom: 2rem;
    }

    .form-inline {
        display: flex;
        gap: 1rem;
    }

    .form-control {
        flex: 1;
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

    .btn-danger-outline {
        background: transparent;
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #f87171;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-danger-outline:hover {
        background: rgba(239, 68, 68, 0.15);
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

<div class="transaction-layout">
    <!-- Member Info Sidebar -->
    <div class="glass-card">
        <div class="card-title">Profil Anggota</div>
        <ul class="info-list">
            <li>
                <span class="info-label">ID Anggota</span>
                <span class="info-value"><code>{{ $member->member_id }}</code></span>
            </li>
            <li>
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ $member->member_name }}</span>
            </li>
            <li>
                <span class="info-label">Email</span>
                <span class="info-value">{{ $member->member_email ?? '-' }}</span>
            </li>
            <li>
                <span class="info-label">Masa Berlaku</span>
                <span class="info-value">{{ $member->expire_date->toDateString() }}</span>
            </li>
        </ul>
        <div style="margin-top: 2rem; border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
            <a href="{{ route('admin.circulation.index') }}" style="color: #94a3b8; text-decoration: none; font-weight: 500;">&larr; Selesai Transaksi</a>
        </div>
    </div>

    <!-- Active Actions & Loan List -->
    <div class="transaction-area">
        <!-- Checkout Form -->
        <div class="glass-card checkout-section">
            <div class="card-title" style="margin-bottom: 1rem;">Pinjamkan Buku (Checkout)</div>
            <form action="{{ route('admin.circulation.checkout', ['member_id' => $member->member_id]) }}" method="POST">
                @csrf
                <div class="form-inline">
                    <input type="text" name="item_code" class="form-control" placeholder="Masukkan Kode Barcode Eksemplar Buku (Contoh: B00002)" required autofocus>
                    <button type="submit" class="btn-primary">Pinjamkan</button>
                </div>
            </form>
        </div>

        <!-- Current Loans -->
        <div class="glass-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <div class="card-title" style="margin: 0;">Buku yang Sedang Dipinjam</div>
                @if($member->loans->isNotEmpty())
                    <a href="{{ route('admin.circulation.receipt', ['member_id' => $member->member_id]) }}" target="_blank" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem; text-decoration: none;">Cetak Struk</a>
                @endif
            </div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Kode Eksemplar</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Kembali</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($member->loans as $loan)
                            <tr>
                                <td><code>{{ $loan->item_code }}</code></td>
                                <td>{{ $loan->loan_date->toDateString() }}</td>
                                <td>{{ $loan->due_date->toDateString() }}</td>
                                <td>
                                    <form action="{{ route('admin.circulation.checkin', ['loan_id' => $loan->loan_id]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-danger-outline">Kembalikan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b; padding: 2rem 0;">Tidak ada pinjaman aktif saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
