@extends('layouts.admin')

@section('title', 'Transaksi: ' . $member->member_name . ' - SIRITA Admin')
@section('header_title', 'Transaksi Sirkulasi')

@section('content')

@if(session('success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
    <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
</div>
@endif

<div class="transaction-layout">
    
    {{-- Profil Anggota (Sidebar Kiri) --}}
    <div class="glass-card member-info-card">
        <div class="card-title"><i class="fas fa-id-card text-primary"></i> Profil Anggota</div>
        
        <div class="member-avatar-wrap">
            <div class="avatar-fallback">
                <i class="fas fa-user-circle"></i>
            </div>
        </div>

        <ul class="info-list">
            <li>
                <span class="info-label">ID Anggota</span>
                <span class="info-value"><code>{{ $member->member_id }}</code></span>
            </li>
            <li>
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value"><strong>{{ $member->member_name }}</strong></span>
            </li>
            <li>
                <span class="info-label">Tipe Anggota</span>
                <span class="info-value"><span class="badge badge-type">{{ $member->memberType?->member_type_name ?? 'Standar' }}</span></span>
            </li>
            <li>
                <span class="info-label">Email</span>
                <span class="info-value">{{ $member->member_email ?? '-' }}</span>
            </li>
            <li>
                <span class="info-label">Masa Berlaku</span>
                @php
                    $isExpired = $member->expire_date && $member->expire_date->isPast();
                @endphp
                <span class="info-value {{ $isExpired ? 'text-danger' : 'text-success' }}">
                    {{ $member->expire_date ? $member->expire_date->format('d M Y') : '-' }}
                    @if($isExpired) (Expired) @endif
                </span>
            </li>
        </ul>
        
        <div class="finish-btn-wrap">
            <a href="{{ route('admin.circulation.index') }}" class="btn-finish-transaction">
                <i class="fas fa-check-double"></i> Selesai Transaksi
            </a>
        </div>
    </div>

    {{-- Area Transaksi Utama (Kanan) --}}
    <div class="transaction-area">
        
        {{-- Navigasi Tab --}}
        <div class="tabs-navigation">
            <button class="tab-nav-btn active" data-target="#tab-checkout">
                <i class="fas fa-book-reader"></i> Pinjam Buku
            </button>
            <button class="tab-nav-btn" data-target="#tab-current">
                <i class="fas fa-bookmark"></i> Pinjaman Aktif
                @if(count($activeLoans) > 0)
                    <span class="badge-count">{{ count($activeLoans) }}</span>
                @endif
            </button>
            <button class="tab-nav-btn" data-target="#tab-fines">
                <i class="fas fa-hand-holding-usd"></i> Denda
                @php
                    $unpaidFines = $fines->where('credit', 0)->count();
                @endphp
                @if($unpaidFines > 0)
                    <span class="badge-count count-danger">{{ $unpaidFines }}</span>
                @endif
            </button>
            <button class="tab-nav-btn" data-target="#tab-history">
                <i class="fas fa-history"></i> Sejarah Pinjam
            </button>
        </div>

        {{-- Isi Konten Tab --}}
        <div class="tabs-content">
            
            {{-- TAB 1: Checkout/Pinjam Buku --}}
            <div class="tab-pane active" id="tab-checkout">
                <div class="glass-card shadow-inner">
                    <h3 class="pane-title">Pinjamkan Buku (Checkout)</h3>
                    <p class="pane-subtitle">Scan barcode buku atau masukkan kode eksemplar di bawah untuk memproses peminjaman.</p>
                    
                    <form action="{{ route('admin.circulation.checkout', ['member_id' => $member->member_id]) }}" method="POST">
                        @csrf
                        <div class="form-inline">
                            <input type="text" name="item_code" class="form-control" placeholder="Masukkan Kode Eksemplar (Contoh: B00001)" required autofocus>
                            <button type="submit" class="btn-primary"><i class="fas fa-plus"></i> Pinjamkan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TAB 2: Pinjaman Aktif --}}
            <div class="tab-pane" id="tab-current">
                <div class="glass-card shadow-inner">
                    <div class="pane-header-row">
                        <div>
                            <h3 class="pane-title">Daftar Pinjaman Aktif</h3>
                            <p class="pane-subtitle">Daftar buku yang saat ini sedang dipinjam oleh anggota.</p>
                        </div>
                        @if(count($activeLoans) > 0)
                            <a href="{{ route('admin.circulation.receipt', ['member_id' => $member->member_id]) }}" target="_blank" class="btn-receipt">
                                <i class="fas fa-print"></i> Cetak Struk Peminjaman
                            </a>
                        @endif
                    </div>

                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Kode Eksemplar</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th style="width: 130px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeLoans as $loan)
                                @php
                                    $overdue = \Carbon\Carbon::parse($loan->due_date)->isPast();
                                @endphp
                                <tr class="{{ $overdue ? 'row-overdue' : '' }}">
                                    <td><code>{{ $loan->item_code }}</code></td>
                                    <td>
                                        <strong>{{ $loan->item?->bibliography?->title ?? 'Judul Tidak Ditemukan' }}</strong>
                                        @if($overdue) <span class="badge badge-danger-sm">Terlambat</span> @endif
                                    </td>
                                    <td>{{ $loan->loan_date ? \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') : '-' }}</td>
                                    <td class="{{ $overdue ? 'text-danger-strong' : '' }}">
                                        {{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y') : '-' }}
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:.35rem;justify-content:center;">
                                            <form action="{{ route('admin.circulation.checkin', ['loan_id' => $loan->loan_id]) }}" method="POST" style="margin:0;">
                                                @csrf
                                                <button type="submit" class="btn-action btn-return" onclick="return confirm('Kembalikan eksemplar ini?')">
                                                    <i class="fas fa-undo"></i> Kembalikan
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 2.5rem 0;">
                                        <i class="fas fa-bookmark" style="font-size: 2rem; opacity: .2; display: block; margin-bottom: .5rem;"></i>
                                        Tidak ada peminjaman aktif saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 3: Denda --}}
            <div class="tab-pane" id="tab-fines">
                <div class="glass-card shadow-inner">
                    <h3 class="pane-title">Rekam Denda Anggota</h3>
                    <p class="pane-subtitle">Catatan denda keterlambatan buku yang belum atau sudah dibayar.</p>

                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Denda</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Denda</th>
                                    <th>Status Pembayaran</th>
                                    <th style="width: 130px; text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fines as $fine)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($fine->fines_date)->format('d M Y') }}</td>
                                    <td>{{ $fine->description }}</td>
                                    <td><strong>Rp {{ number_format($fine->debet) }}</strong></td>
                                    <td>
                                        @if($fine->credit >= $fine->debet)
                                            <span class="badge status-active">Lunas</span>
                                        @else
                                            <span class="badge status-inactive">Belum Bayar</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:.35rem;justify-content:center;">
                                            @if($fine->credit < $fine->debet)
                                                <form action="{{ route('admin.circulation.fines.pay', ['fines_id' => $fine->fines_id]) }}" method="POST" style="margin:0;">
                                                    @csrf
                                                    <button type="submit" class="btn-action btn-pay" onclick="return confirm('Bayar lunas denda?')">
                                                        <i class="fas fa-check"></i> Bayar
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted-sm"><i class="fas fa-check-circle text-success"></i> Selesai</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 2.5rem 0;">
                                        <i class="fas fa-wallet" style="font-size: 2rem; opacity: .2; display: block; margin-bottom: .5rem;"></i>
                                        Anggota bersih dari denda.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TAB 4: Sejarah Peminjaman --}}
            <div class="tab-pane" id="tab-history">
                <div class="glass-card shadow-inner">
                    <h3 class="pane-title">Sejarah Peminjaman</h3>
                    <p class="pane-subtitle">Menampilkan 30 riwayat peminjaman terakhir yang telah dikembalikan.</p>

                    <div style="overflow-x: auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Kode Eksemplar</th>
                                    <th>Judul Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Tanggal Kembali</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loanHistory as $history)
                                <tr>
                                    <td><code>{{ $history->item_code }}</code></td>
                                    <td><strong>{{ $history->item?->bibliography?->title ?? 'Judul Tidak Ditemukan' }}</strong></td>
                                    <td>{{ \Carbon\Carbon::parse($history->loan_date)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($history->due_date)->format('d M Y') }}</td>
                                    <td>
                                        <span class="text-success">{{ \Carbon\Carbon::parse($history->return_date)->format('d M Y') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; color: #64748b; padding: 2.5rem 0;">
                                        <i class="fas fa-history" style="font-size: 2rem; opacity: .2; display: block; margin-bottom: .5rem;"></i>
                                        Belum memiliki riwayat peminjaman.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
/* Grid Layout */
.transaction-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 1.75rem;
    align-items: start;
}
@media (max-width: 900px) {
    .transaction-layout { grid-template-columns: 1fr; }
}

/* Glass Card */
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 1.75rem;
    border-radius: 16px;
    backdrop-filter: blur(12px);
    box-sizing: border-box;
}
.shadow-inner {
    border-top: 1px solid rgba(255,255,255,.05);
}

/* Profil Sidebar */
.card-title {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: white;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.member-avatar-wrap {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
}
.avatar-fallback {
    font-size: 5rem;
    color: #64748b;
}
.info-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1.75rem;
    display: flex;
    flex-direction: column;
    gap: .95rem;
}
.info-label {
    color: #64748b;
    font-size: .8rem;
    display: block;
    margin-bottom: .15rem;
    font-weight: 500;
}
.info-value {
    color: #f1f5f9;
    font-size: .9rem;
    word-break: break-word;
}
.finish-btn-wrap {
    border-top: 1px solid var(--border-color);
    padding-top: 1.25rem;
}
.btn-finish-transaction {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: .5rem;
    width: 100%;
    padding: .75rem;
    border-radius: 10px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    text-decoration: none;
    font-weight: 700;
    font-size: .875rem;
    transition: opacity .2s;
    box-sizing: border-box;
}
.btn-finish-transaction:hover { opacity: .9; }

/* Tabs Navigation */
.tabs-navigation {
    display: flex;
    gap: .5rem;
    margin-bottom: 1.25rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: .25rem;
    overflow-x: auto;
}
.tab-nav-btn {
    display: flex;
    align-items: center;
    gap: .45rem;
    background: transparent;
    border: none;
    color: #94a3b8;
    padding: .75rem 1.2rem;
    font-size: .875rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
}
.tab-nav-btn:hover { color: white; }
.tab-nav-btn.active {
    color: var(--primary);
    border-bottom-color: var(--primary);
}

/* Tabs Pane */
.tab-pane {
    display: none;
}
.tab-pane.active {
    display: block;
    animation: fadeIn .2s ease;
}

/* Pane Header & Subtitle */
.pane-title { font-size: 1.2rem; font-weight: 700; color: white; margin: 0 0 .25rem; }
.pane-subtitle { color: #64748b; font-size: .85rem; margin: 0 0 1.5rem; }
.pane-header-row {
    display: flex; justify-content: space-between; align-items: flex-start;
    gap: 1rem; flex-wrap: wrap; margin-bottom: .5rem;
}

/* Checkout Form inline */
.form-inline {
    display: flex;
    gap: .6rem;
}
.form-control {
    flex: 1;
    padding: .7rem 1.1rem;
    border-radius: 8px;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid var(--border-color);
    color: white;
    outline: none;
    font-size: .875rem;
}
.form-control:focus { border-color: var(--primary); }

.btn-primary {
    background: var(--primary);
    border: none; color: white;
    padding: .7rem 1.4rem;
    border-radius: 8px; font-weight: 600;
    font-size: .875rem; cursor: pointer;
    display: inline-flex; align-items: center; gap: .4rem;
    transition: background .2s;
}
.btn-primary:hover { background: var(--primary-hover); }

.btn-receipt {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .5rem 1rem; border-radius: 8px;
    border: 1px solid var(--border-color);
    background: rgba(99, 102, 241, 0.1);
    color: #a5b4fc; text-decoration: none;
    font-weight: 600; font-size: .82rem;
    transition: all .2s;
}
.btn-receipt:hover { background: var(--primary); color: white; }

/* Table style */
.admin-table {
    width: 100%; border-collapse: collapse;
}
.admin-table th, .admin-table td {
    padding: .85rem 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
    font-size: .85rem;
}
.admin-table th { color: #94a3b8; font-weight: 500; }
.admin-table td { color: white; }
.admin-table tbody tr:hover { background: rgba(255,255,255,.02); }

.row-overdue { background: rgba(239, 68, 68, 0.05); }

/* Badges */
.badge {
    display: inline-block;
    padding: .15rem .55rem;
    border-radius: 999px;
    font-size: .7rem;
    font-weight: 700;
}
.status-active  { background: rgba(16, 185, 129, 0.12); color: #34d399; }
.status-inactive { background: rgba(239, 68, 68, 0.12); color: #f87171; }

.badge-count {
    background: var(--primary); color: white;
    border-radius: 999px; font-size: .7rem; font-weight: 700;
    padding: .05rem .4rem; margin-left: .2rem;
}
.badge-count.count-danger { background: #ef4444; }

.badge-danger-sm {
    background: rgba(239, 68, 68, 0.15); color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.3);
    font-size: .65rem; font-weight: 700;
    padding: .05rem .35rem; border-radius: 4px;
    margin-left: .3rem; text-transform: uppercase;
}
.badge-type {
    background: rgba(99,102,241,.12); color: #a5b4fc; border: 1px solid rgba(99,102,241,.25);
    padding: .15rem .5rem; font-size: .75rem; font-weight: 500;
}

.text-danger-strong { color: #f87171 !important; font-weight: 600; }
.text-muted-sm { color: #64748b; font-size: .8rem; }

/* Buttons Action */
.btn-action {
    display: inline-flex; align-items: center; gap: .3rem;
    padding: .4rem .7rem; border-radius: 6px;
    text-decoration: none; font-size: .8rem; font-weight: 600;
    border: 1px solid var(--border-color);
    color: #cfd8dc; transition: all .2s;
    background: transparent; cursor: pointer;
}
.btn-return:hover { border-color: #f87171; color: #f87171; background: rgba(239,68,68,.05); }
.btn-pay:hover { border-color: #10b981; color: #10b981; background: rgba(16,185,129,.05); }

/* Alerts */
.alert {
    display: flex; align-items: center; gap: .6rem;
    padding: .85rem 1.2rem; border-radius: 10px;
    margin-bottom: 1.25rem; font-size: .875rem;
}
.alert-success { background: rgba(16,185,129,.1); color: #34d399; border: 1px solid rgba(16,185,129,.25); }
.alert-danger  { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.25); }
.alert-close { margin-left:auto; background:none; border:none; cursor:pointer; font-size:1.1rem; color:inherit; }

.text-primary { color: var(--primary); }
.text-success { color: #34d399; }
.text-danger  { color: #f87171; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabBtns = document.querySelectorAll('.tab-nav-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');

    // Restore last active tab from localStorage if available, or default to checkout
    const activeTab = localStorage.getItem('circulation_active_tab') || '#tab-checkout';
    
    // Set initial active state
    tabBtns.forEach(btn => {
        if (btn.getAttribute('data-target') === activeTab) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    tabPanes.forEach(pane => {
        if ('#' + pane.id === activeTab) {
            pane.classList.add('active');
        } else {
            pane.classList.remove('active');
        }
    });

    // Tab Switch handler
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const target = this.getAttribute('data-target');
            
            // Toggle buttons class
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Toggle panes class
            tabPanes.forEach(p => {
                if ('#' + p.id === target) {
                    p.classList.add('active');
                } else {
                    p.classList.remove('active');
                }
            });

            // Save active tab
            localStorage.setItem('circulation_active_tab', target);
        });
    });
});
</script>
@endpush
