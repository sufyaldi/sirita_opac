@extends('opac.templates.classic.layout')

@section('title', 'Dashboard Keanggotaan - SIRITA OPAC')

@section('content')
<style>
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .welcome-greeting h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        background: linear-gradient(to right, #818cf8, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .welcome-greeting p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    .btn-logout {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.25rem;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        border-radius: 10px;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        font-family: 'Outfit', sans-serif;
    }

    .btn-logout:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: rgba(239, 68, 68, 0.5);
    }

    /* Stat Cards Row */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        transition: transform 0.3s;
    }

    .stat-card:hover { transform: translateY(-3px); }

    .stat-card .stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-card .stat-label {
        color: #64748b;
        font-size: 0.8rem;
    }

    /* Section blocks */
    .section-block {
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        margin-bottom: 1.5rem;
    }

    .section-block h2 {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0 0 1.25rem 0;
        color: #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    /* Profile grid */
    .profile-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .profile-item label {
        display: block;
        font-size: 0.75rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.25rem;
    }

    .profile-item span {
        color: #e2e8f0;
        font-weight: 500;
        font-size: 0.95rem;
    }

    /* Loan table */
    .loan-table {
        width: 100%;
        border-collapse: collapse;
    }

    .loan-table th {
        color: #475569;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: left;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .loan-table td {
        padding: 0.875rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: #cbd5e1;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .loan-table tr:last-child td { border-bottom: none; }

    .loan-table tr:hover td {
        background: rgba(99, 102, 241, 0.04);
    }

    .badge-overdue {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        background: rgba(239, 68, 68, 0.15);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-ok {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.25);
        color: #6ee7b7;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-returned {
        display: inline-block;
        padding: 0.2rem 0.6rem;
        background: rgba(100, 116, 139, 0.2);
        border: 1px solid rgba(100, 116, 139, 0.3);
        color: #94a3b8;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #475569;
    }

    .empty-state svg { margin-bottom: 1rem; opacity: 0.4; }
    .empty-state p { margin: 0; font-size: 0.95rem; }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .expire-badge {
        display: inline-block;
        padding: 0.2rem 0.7rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .expire-valid   { background: rgba(16,185,129,0.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,0.3); }
    .expire-expired { background: rgba(239,68,68,0.12);  color: #fca5a5; border: 1px solid rgba(239,68,68,0.3); }

    .fines-total {
        font-size: 1.4rem;
        font-weight: 700;
        color: #fca5a5;
    }
</style>

<div class="dashboard-header">
    <div class="welcome-greeting">
        <h1>Selamat Datang, {{ $member->member_name }}</h1>
        <p>ID Anggota: {{ $member->member_id }} &nbsp;·&nbsp; Login terakhir: {{ $member->last_login ? $member->last_login->format('d M Y, H:i') : '-' }}</p>
    </div>
    <form action="{{ route('opac.member.logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn-logout">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
            </svg>
            Keluar
        </button>
    </form>
</div>

@if(session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
@endif

{{-- Stat Cards --}}
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(99, 102, 241, 0.15);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        </div>
        <div class="stat-value" style="color: #818cf8;">{{ $activeLoans->count() }}</div>
        <div class="stat-label">Buku Dipinjam</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.12);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#6ee7b7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </div>
        <div class="stat-value" style="color: #6ee7b7;">{{ $loanHistory->count() }}</div>
        <div class="stat-label">Riwayat Pengembalian</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fca5a5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        </div>
        <div class="stat-value" style="color: #fca5a5;">Rp {{ number_format($totalFines, 0, ',', '.') }}</div>
        <div class="stat-label">Denda Belum Lunas</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(251, 191, 36, 0.1);">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div class="stat-value" style="color: #fbbf24; font-size: 1rem; padding-top: 0.5rem;">
            @if($member->expire_date)
                {{ $member->expire_date->format('d M Y') }}
            @else
                Seumur Hidup
            @endif
        </div>
        <div class="stat-label">Masa Berlaku Keanggotaan</div>
    </div>
</div>

{{-- Profile Info --}}
<div class="section-block">
    <h2>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Data Anggota
    </h2>
    <div class="profile-grid">
        <div class="profile-item">
            <label>Nama Lengkap</label>
            <span>{{ $member->member_name }}</span>
        </div>
        <div class="profile-item">
            <label>Nomor Anggota</label>
            <span>{{ $member->member_id }}</span>
        </div>
        <div class="profile-item">
            <label>Jenis Anggota</label>
            <span>{{ $member->memberType->member_type_name ?? '-' }}</span>
        </div>
        <div class="profile-item">
            <label>Email</label>
            <span>{{ $member->member_email ?: '-' }}</span>
        </div>
        <div class="profile-item">
            <label>Nomor Telepon</label>
            <span>{{ $member->member_phone ?: '-' }}</span>
        </div>
        <div class="profile-item">
            <label>Institusi</label>
            <span>{{ $member->inst_name ?: '-' }}</span>
        </div>
        <div class="profile-item">
            <label>Anggota Sejak</label>
            <span>{{ $member->member_since_date ? $member->member_since_date->format('d M Y') : '-' }}</span>
        </div>
        <div class="profile-item">
            <label>Status Keanggotaan</label>
            <span>
                @if(!$member->expire_date || $member->expire_date->isFuture())
                    <span class="expire-badge expire-valid">✓ Aktif</span>
                @else
                    <span class="expire-badge expire-expired">✗ Kedaluwarsa</span>
                @endif
            </span>
        </div>
    </div>
</div>

{{-- Active Loans --}}
<div class="section-block">
    <h2>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        Buku yang Sedang Dipinjam
    </h2>

    @if($activeLoans->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            <p>Tidak ada buku yang sedang dipinjam</p>
        </div>
    @else
        <table class="loan-table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Kode Eksemplar</th>
                    <th>Tgl. Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeLoans as $loan)
                    @php $isOverdue = $loan->due_date && now()->gt($loan->due_date); @endphp
                    <tr>
                        <td style="color: #e2e8f0; font-weight: 500;">
                            {{ $loan->item->biblio->title ?? '-' }}
                        </td>
                        <td><code style="color: #a5b4fc; font-size: 0.82rem;">{{ $loan->item->item_code ?? '-' }}</code></td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                        <td style="{{ $isOverdue ? 'color: #fca5a5;' : '' }}">
                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                        </td>
                        <td>
                            @if($isOverdue)
                                <span class="badge-overdue">Terlambat {{ now()->diffInDays($loan->due_date) }} hari</span>
                            @else
                                <span class="badge-ok">{{ now()->diffInDays($loan->due_date) }} hari lagi</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Fines --}}
@if($fines->isNotEmpty())
<div class="section-block" style="border-color: rgba(239, 68, 68, 0.2);">
    <h2>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fca5a5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Denda Belum Dibayar
    </h2>
    <table class="loan-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th style="text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fines as $fine)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($fine->fines_date)->format('d M Y') }}</td>
                    <td>{{ $fine->description ?: 'Denda keterlambatan' }}</td>
                    <td style="text-align: right; color: #fca5a5; font-weight: 600;">Rp {{ number_format($fine->debet - $fine->credit, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right; color: #94a3b8; font-weight: 600; padding-top: 1rem;">Total Denda:</td>
                <td style="text-align: right; padding-top: 1rem;"><span class="fines-total">Rp {{ number_format($totalFines, 0, ',', '.') }}</span></td>
            </tr>
        </tfoot>
    </table>
    <p style="color: #64748b; font-size: 0.82rem; margin: 1rem 0 0 0;">⚠ Silakan bayarkan denda di meja sirkulasi perpustakaan.</p>
</div>
@endif

{{-- Loan History --}}
<div class="section-block">
    <h2>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        Riwayat Peminjaman (20 Terakhir)
    </h2>

    @if($loanHistory->isEmpty())
        <div class="empty-state">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            <p>Belum ada riwayat peminjaman</p>
        </div>
    @else
        <table class="loan-table">
            <thead>
                <tr>
                    <th>Judul Buku</th>
                    <th>Tgl. Pinjam</th>
                    <th>Tgl. Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loanHistory as $loan)
                    <tr>
                        <td style="color: #e2e8f0; font-weight: 500;">{{ $loan->item->biblio->title ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</td>
                        <td><span class="badge-returned">Dikembalikan</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
