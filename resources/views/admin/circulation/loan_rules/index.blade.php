@extends('layouts.admin')

@section('title', 'Aturan Peminjaman - SIRITA Admin')
@section('header_title', 'Aturan Peminjaman Spesifik')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
</div>
@endif

<div class="glass-card">
    <div class="toolbar">
        <form action="{{ route('admin.circulation.loan-rules.index') }}" method="GET" class="search-form">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari tipe anggota/koleksi...">
            <button type="submit" class="btn-primary">Cari</button>
        </form>
        <a href="{{ route('admin.circulation.loan-rules.create') }}" class="btn-primary">
            <i class="fas fa-plus"></i> Tambah Aturan Baru
        </a>
    </div>

    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tipe Anggota</th>
                    <th>Tipe Koleksi</th>
                    <th>GMD</th>
                    <th>Batas Pinjam</th>
                    <th>Durasi Pinjam</th>
                    <th>Limit Perpanjang</th>
                    <th>Denda / Hari</th>
                    <th>Grace Periode</th>
                    <th style="width: 140px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                <tr>
                    <td><strong>{{ $rule->memberType?->member_type_name ?? '-' }}</strong></td>
                    <td><span class="badge badge-type">{{ $rule->collType?->coll_type_name ?? '-' }}</span></td>
                    <td><code>{{ $rule->gmd_id == 0 ? 'Semua GMD' : ($rule->gmd?->gmd_name ?? 'GMD #'.$rule->gmd_id) }}</code></td>
                    <td>{{ $rule->loan_limit }} buku</td>
                    <td>{{ $rule->loan_periode }} hari</td>
                    <td>{{ $rule->reborrow_limit }}x</td>
                    <td>Rp {{ number_format($rule->fine_each_day) }}</td>
                    <td>{{ $rule->grace_periode }} hari</td>
                    <td>
                        <div style="display: flex; gap: 0.4rem; justify-content: center;">
                            <a href="{{ route('admin.circulation.loan-rules.edit', $rule->loan_rules_id) }}" class="btn-action btn-edit">
                                <i class="fas fa-pen"></i> Ubah
                            </a>
                            <form action="{{ route('admin.circulation.loan-rules.destroy', $rule->loan_rules_id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Hapus aturan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #64748b; padding: 2.5rem 0;">
                        <i class="fas fa-gavel" style="font-size: 2rem; opacity: .2; display: block; margin-bottom: .5rem;"></i>
                        Belum ada aturan peminjaman spesifik.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $rules->firstItem() ?? 0 }}–{{ $rules->lastItem() ?? 0 }} dari {{ $rules->total() }} aturan
        </div>
        <div class="pagination">
            {{ $rules->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Glass Card */
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 1.75rem;
    border-radius: 16px;
    backdrop-filter: blur(12px);
}
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.search-form { display: flex; gap: .5rem; }

/* Form Controls */
.form-control {
    padding: .55rem 1rem;
    border-radius: 8px;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid var(--border-color);
    color: white;
    outline: none;
    font-size: .875rem;
}
.form-control:focus { border-color: var(--primary); }

/* Buttons */
.btn-primary {
    background: var(--primary);
    border: none; color: white;
    padding: .55rem 1.1rem;
    border-radius: 8px; font-weight: 600;
    font-size: .875rem; cursor: pointer;
    display: inline-flex; align-items: center; gap: .4rem;
    transition: background .2s;
    text-decoration: none;
}
.btn-primary:hover { background: var(--primary-hover); }

.btn-action {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .35rem .65rem; border-radius: 6px;
    text-decoration: none; font-size: .8rem; font-weight: 500;
    border: 1px solid var(--border-color);
    color: #cfd8dc; transition: all .2s;
    background: transparent; cursor: pointer;
}
.btn-edit:hover   { border-color: #60a5fa; color: #60a5fa; }
.btn-delete:hover { border-color: #f87171; color: #f87171; }

/* Table */
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th, .admin-table td {
    padding: .85rem 1rem; text-align: left;
    border-bottom: 1px solid var(--border-color);
    font-size: .85rem;
}
.admin-table th { color: #94a3b8; font-weight: 500; }
.admin-table td { color: white; }
.admin-table tbody tr:hover { background: rgba(255,255,255,.02); }

/* Badges */
.badge {
    display: inline-block;
    padding: .15rem .55rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 700;
}
.badge-type {
    background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.25);
    font-size: .75rem; font-weight: 500;
}

/* Pagination */
.pagination-wrap {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 1.5rem; flex-wrap: wrap; gap: .5rem;
}
.pagination-info { color: #64748b; font-size: .8rem; }
.pagination { display: flex; gap: .35rem; }
.pagination a, .pagination span {
    padding: .35rem .7rem; border-radius: 6px;
    border: 1px solid var(--border-color);
    background: var(--card-bg); color: white; text-decoration: none;
    font-size: .85rem;
}
.pagination a:hover { border-color: var(--primary); color: var(--primary); }
.pagination .active span { background: var(--primary); border-color: var(--primary); }

/* Alert */
.alert {
    display: flex; align-items: center; gap: .6rem;
    padding: .85rem 1.2rem; border-radius: 10px;
    margin-bottom: 1.25rem; font-size: .875rem;
}
.alert-success { background: rgba(16,185,129,.1); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
.alert-close { margin-left:auto; background:none; border:none; cursor:pointer; font-size:1.1rem; color:inherit; }
</style>
@endpush
