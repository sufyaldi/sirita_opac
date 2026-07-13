@extends('layouts.admin')

@section('title', 'Stok Opname - SIRITA Admin')
@section('header_title', 'Stok Opname (Stock Take)')

@section('content')
<style>
    .stocktake-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .stocktake-grid {
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
        text-align: center;
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

    .badge-finished {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
    }
</style>

<div class="stocktake-grid">
    <!-- Start New Session Form -->
    <div class="glass-card">
        <div class="card-title">Mulai Sesi Baru</div>
        <form action="{{ route('admin.stock-take.start') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="session_name" class="form-label">Nama Sesi Stok Opname</label>
                <input type="text" id="session_name" name="session_name" class="form-control" placeholder="Contoh: Stok Opname Buku IT 2026" required>
            </div>
            <button type="submit" class="btn-primary">Mulai Stok Opname</button>
        </form>
    </div>

    <!-- History of Finished Sessions -->
    <div class="glass-card">
        <div class="card-title">Riwayat Sesi Stok Opname</div>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nama Sesi</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($finishedSessions as $session)
                        <tr>
                            <td><strong>{{ $session->session_name }}</strong></td>
                            <td><code>{{ $session->updated_at->toDateString() }}</code></td>
                            <td>
                                <span class="badge badge-finished">Selesai</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada riwayat sesi stok opname yang diselesaikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $finishedSessions->links() }}
        </div>
    </div>
</div>
@endsection
