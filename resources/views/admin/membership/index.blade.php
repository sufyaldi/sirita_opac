@extends('layouts.admin')

@section('title', 'Keanggotaan - SIRITA Admin')
@section('header_title', 'Manajemen Keanggotaan')

@section('content')

{{-- ===== Summary Stats ===== --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon total"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($totalMembers) }}</div>
            <div class="stat-label">Total Anggota</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon active"><i class="fas fa-user-check"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($activeMembers) }}</div>
            <div class="stat-label">Anggota Aktif</div>
        </div>
    </div>
    <div class="stat-card {{ $expiredMembers > 0 ? 'highlight-expired' : '' }}">
        <div class="stat-icon expired"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
            <div class="stat-value">{{ number_format($expiredMembers) }}</div>
            <div class="stat-label">Anggota Expired</div>
        </div>
    </div>
</div>

{{-- ===== Flash Message ===== --}}
@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
</div>
@endif

{{-- ===== Bulk Extend Form ===== --}}
<form id="extendForm" action="{{ route('admin.membership.extend') }}" method="POST">
@csrf
<div class="glass-card">

    {{-- Toolbar --}}
    <div class="toolbar">
        {{-- Search + Filters --}}
        <div class="filter-group">
            <form id="filterForm" action="{{ route('admin.membership.index') }}" method="GET" class="filter-row">
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Cari nama / ID anggota..."
                    onchange="this.form.submit()">

                <select name="member_type_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Semua Tipe</option>
                    @foreach($memberTypes as $type)
                        <option value="{{ $type->member_type_id }}"
                            {{ request('member_type_id') == $type->member_type_id ? 'selected' : '' }}>
                            {{ $type->member_type_name }}
                        </option>
                    @endforeach
                </select>

                @if(request()->boolean('expired'))
                    <input type="hidden" name="expired" value="1">
                @endif
            </form>

            <a href="{{ route('admin.membership.index', array_merge(request()->except(['expired','page']), ['expired' => request()->boolean('expired') ? null : '1'])) }}"
               class="btn-filter {{ request()->boolean('expired') ? 'btn-filter-active' : '' }}">
                <i class="fas fa-user-clock"></i>
                {{ request()->boolean('expired') ? 'Semua Anggota' : 'Anggota Expired' }}
                @if(!request()->boolean('expired') && $expiredMembers > 0)
                    <span class="badge-count-sm">{{ $expiredMembers }}</span>
                @endif
            </a>
        </div>

        {{-- Right actions --}}
        <div class="action-group">
            {{-- Bulk extend controls (only visible in expired mode) --}}
            <div id="extendControls" class="{{ request()->boolean('expired') ? '' : 'd-none' }}" style="display:flex;align-items:center;gap:.5rem;">
                <select name="extend_months" class="form-control form-control-sm">
                    @foreach([1,3,6,12,24] as $m)
                        <option value="{{ $m }}">+{{ $m }} Bulan</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-extend" onclick="return confirmExtend()">
                    <i class="fas fa-calendar-plus"></i> Perpanjang Terpilih
                </button>
            </div>
            <a href="{{ route('admin.membership.create') }}" class="btn-primary">
                <i class="fas fa-plus"></i> Tambah Anggota
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    @if(request()->boolean('expired'))
                    <th style="width:40px;">
                        <input type="checkbox" id="checkAll" title="Pilih semua">
                    </th>
                    @endif
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Tipe Keanggotaan</th>
                    <th>Email</th>
                    <th>Masa Berlaku</th>
                    <th>Terakhir Diperbarui</th>
                    <th style="width:130px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                @php
                    $isExpired = $member->expire_date && $member->expire_date->isPast();
                    $expiringSoon = !$isExpired && $member->expire_date && $member->expire_date->diffInDays(now()) <= 30;
                @endphp
                <tr class="{{ $isExpired ? 'row-expired' : ($expiringSoon ? 'row-expiring' : '') }}">
                    @if(request()->boolean('expired'))
                    <td>
                        <input type="checkbox" name="member_ids[]" value="{{ $member->member_id }}" class="member-cb">
                    </td>
                    @endif
                    <td><code>{{ $member->member_id }}</code></td>
                    <td>
                        <strong>{{ $member->member_name }}</strong>
                        @if($isExpired)
                            <span class="badge badge-expired">Expired</span>
                        @elseif($expiringSoon)
                            <span class="badge badge-expiring">Segera Expired</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-type">
                            {{ $member->memberType?->member_type_name ?? '-' }}
                        </span>
                    </td>
                    <td>{{ $member->member_email ?? '-' }}</td>
                    <td class="{{ $isExpired ? 'text-expired' : '' }}">
                        {{ $member->expire_date ? $member->expire_date->format('d M Y') : '-' }}
                    </td>
                    <td class="text-muted-sm">
                        {{ $member->last_update ? \Carbon\Carbon::parse($member->last_update)->format('d M Y') : '-' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;justify-content:center;">
                            <a href="{{ route('admin.membership.edit', $member->member_id) }}"
                               class="btn-action btn-edit" title="Edit">
                                <i class="fas fa-pen"></i> Ubah
                            </a>
                            <form action="{{ route('admin.membership.destroy', $member->member_id) }}"
                                  method="POST" style="margin:0;"
                                  onsubmit="return confirm('Hapus anggota {{ $member->member_name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ request()->boolean('expired') ? 8 : 7 }}"
                        style="text-align:center;color:#64748b;padding:2.5rem 0;">
                        <i class="fas fa-users" style="font-size:2rem;opacity:.3;display:block;margin-bottom:.5rem;"></i>
                        {{ request()->boolean('expired') ? 'Tidak ada anggota yang expired.' : 'Belum ada data anggota.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-wrap">
        <div class="pagination-info">
            Menampilkan {{ $members->firstItem() ?? 0 }}–{{ $members->lastItem() ?? 0 }}
            dari {{ $members->total() }} anggota
        </div>
        <div class="pagination">
            {{ $members->appends(request()->query())->links() }}
        </div>
    </div>
</div>
</form>

@push('styles')
<style>
/* ===== Stats ===== */
.stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    padding: 1.1rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    backdrop-filter: blur(10px);
}
.stat-card.highlight-expired { border-color: rgba(239,68,68,.4); }
.stat-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
}
.stat-icon.total   { background: rgba(99,102,241,.15); color: #818cf8; }
.stat-icon.active  { background: rgba(16,185,129,.15); color: #34d399; }
.stat-icon.expired { background: rgba(239,68,68,.15);  color: #f87171; }
.stat-value { font-size: 1.5rem; font-weight: 700; color: white; }
.stat-label { font-size: .8rem; color: #64748b; }

/* ===== Alert ===== */
.alert {
    display: flex; align-items: center; gap: .6rem;
    padding: .85rem 1.2rem; border-radius: 10px;
    margin-bottom: 1rem; font-size: .875rem;
}
.alert-success { background: rgba(16,185,129,.1); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
.alert-close { margin-left:auto; background:none; border:none; cursor:pointer; font-size:1.1rem; color:inherit; }

/* ===== Card & Toolbar ===== */
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 1.75rem;
    border-radius: 18px;
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
.filter-group { display: flex; align-items: center; gap: .6rem; flex-wrap: wrap; }
.filter-row   { display: flex; gap: .5rem; align-items: center; }
.action-group { display: flex; align-items: center; gap: .6rem; }

/* ===== Form Controls ===== */
.form-control {
    padding: .55rem 1rem;
    border-radius: 8px;
    background: rgba(30,41,59,.5);
    border: 1px solid var(--border-color);
    color: white;
    outline: none;
    font-size: .875rem;
    transition: border-color .2s;
}
.form-control:focus { border-color: var(--primary); }
.form-control-sm { padding: .4rem .75rem; font-size: .8rem; }

/* ===== Buttons ===== */
.btn-primary {
    background: var(--primary);
    border: none; color: white;
    padding: .55rem 1.1rem;
    border-radius: 8px; font-weight: 600;
    cursor: pointer; text-decoration: none;
    display: inline-flex; align-items: center; gap: .4rem;
    font-size: .875rem; transition: background .25s;
    white-space: nowrap;
}
.btn-primary:hover { background: var(--primary-hover); }

.btn-filter {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1rem;
    border-radius: 8px;
    border: 1px solid var(--border-color);
    background: transparent;
    color: #94a3b8; text-decoration: none;
    font-size: .85rem; font-weight: 500;
    transition: all .2s; white-space: nowrap;
}
.btn-filter:hover { border-color: #f87171; color: #f87171; }
.btn-filter-active { border-color: #f87171; color: #f87171; background: rgba(239,68,68,.08); }

.btn-extend {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1rem;
    border-radius: 8px; border: none;
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white; font-weight: 600; font-size: .85rem;
    cursor: pointer; white-space: nowrap;
    transition: opacity .2s;
}
.btn-extend:hover { opacity: .88; }

.btn-action {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .35rem .65rem; border-radius: 6px;
    text-decoration: none; font-size: .8rem; font-weight: 500;
    border: 1px solid var(--border-color);
    color: #cfd8dc; transition: all .2s;
    background: transparent; cursor: pointer;
}
.btn-edit:hover  { border-color: #60a5fa; color: #60a5fa; }
.btn-delete:hover{ border-color: #f87171; color: #f87171; }

/* ===== Table ===== */
.admin-table {
    width: 100%; border-collapse: collapse;
}
.admin-table th, .admin-table td {
    padding: .85rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
    font-size: .875rem;
}
.admin-table th { color: #94a3b8; font-weight: 500; }
.admin-table td { color: white; }
.admin-table tbody tr:hover { background: rgba(255,255,255,.03); }

/* Expired / expiring rows */
.row-expired  { background: rgba(239,68,68,.05) !important; }
.row-expiring { background: rgba(245,158,11,.05) !important; }
.text-expired { color: #f87171 !important; }
.text-muted-sm { color: #64748b; font-size: .8rem; }

/* ===== Badges ===== */
.badge {
    display: inline-block;
    padding: .15rem .55rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-left: .3rem;
    vertical-align: middle;
}
.badge-expired  { background: rgba(239,68,68,.15); color: #f87171; border: 1px solid rgba(239,68,68,.3); }
.badge-expiring { background: rgba(245,158,11,.15); color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
.badge-type     { background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.25); font-size: .75rem; font-weight: 500; text-transform: none; letter-spacing: 0; padding: .2rem .65rem; }
.badge-count-sm {
    background: #ef4444; color: white;
    border-radius: 999px; font-size: .7rem; font-weight: 700;
    padding: .05rem .4rem; margin-left: .2rem;
}

/* ===== Pagination ===== */
.pagination-wrap {
    display: flex; justify-content: space-between; align-items: center;
    margin-top: 1.5rem; flex-wrap: wrap; gap: .5rem;
}
.pagination-info { color: #64748b; font-size: .8rem; }
.pagination { display: flex; gap: .35rem; flex-wrap: wrap; }
.pagination a, .pagination span {
    padding: .35rem .7rem;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    background: var(--card-bg);
    color: white; text-decoration: none;
    font-size: .85rem; transition: all .2s;
}
.pagination a:hover { border-color: var(--primary); color: var(--primary); }
.pagination .active span { background: var(--primary); border-color: var(--primary); }

.d-none { display: none !important; }
</style>
@endpush

@push('scripts')
<script>
// Select all checkboxes
const checkAll = document.getElementById('checkAll');
if (checkAll) {
    checkAll.addEventListener('change', function () {
        document.querySelectorAll('.member-cb').forEach(cb => cb.checked = this.checked);
    });
}

// Confirm bulk extend
function confirmExtend() {
    const checked = document.querySelectorAll('.member-cb:checked').length;
    if (checked === 0) {
        alert('Pilih minimal satu anggota terlebih dahulu.');
        return false;
    }
    const months = document.querySelector('[name="extend_months"]').value;
    return confirm(`Perpanjang ${checked} anggota selama ${months} bulan?`);
}
</script>
@endpush

@endsection
