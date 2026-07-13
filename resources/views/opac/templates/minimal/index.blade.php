@extends('layouts.opac')

@section('title', 'Cari Buku - SIRITA OPAC Minimal')

@section('content')
<style>
    /* Desain Minimalis - Latar Belakang Putih & Bersih */
    :root {
        --bg-color: #ffffff;
        --text-color: #1e293b;
        --primary: #0f766e; /* Teal Pekat */
        --primary-hover: #115e59;
        --card-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    body {
        background-color: var(--bg-color) !important;
        color: var(--text-color) !important;
    }

    header {
        background: #ffffff !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .logo {
        background: none !important;
        -webkit-text-fill-color: initial !important;
        color: var(--primary) !important;
    }

    .nav-links a {
        color: #64748b !important;
    }

    .nav-links a:hover {
        color: var(--primary) !important;
    }

    .search-hero {
        background: #f1f5f9 !important;
        border: 1px solid #e2e8f0 !important;
        padding: 3rem 1.5rem !important;
        border-radius: 16px !important;
        margin-bottom: 2.5rem !important;
    }

    .search-hero h1 {
        background: none !important;
        -webkit-text-fill-color: initial !important;
        color: #0f172a !important;
        font-size: 2.4rem !important;
        font-weight: 800 !important;
    }

    .search-input {
        background: #ffffff !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #0f172a !important;
    }

    .search-input:focus {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.15) !important;
    }

    .book-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        border-radius: 12px !important;
        padding: 1.25rem !important;
    }

    .book-card::before {
        display: none !important;
    }

    .book-card:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
        border-color: var(--primary) !important;
    }

    .book-card h3 a {
        color: #0f172a !important;
    }

    .book-card h3 a:hover {
        color: var(--primary) !important;
    }

    .badge {
        background: #f1f5f9 !important;
        color: #0f766e !important;
        border: 1px solid #e2e8f0 !important;
    }

    .btn-detail {
        color: #64748b !important;
    }

    .btn-detail:hover {
        color: var(--primary) !important;
    }

    .sidebar-widget {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05) !important;
        border-radius: 12px !important;
    }

    .widget-title {
        color: #0f172a !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .stat-row {
        border-bottom: 1px dashed #e2e8f0 !important;
    }

    .stat-number {
        color: var(--primary) !important;
    }

    footer {
        background: #ffffff !important;
        border-top: 1px solid #e2e8f0 !important;
        color: #64748b !important;
    }
</style>

<div class="search-hero">
    <h1>Cari Koleksi Perpustakaan</h1>
    <p>Telusuri ribuan judul buku teks, referensi akademik, dan terbitan berseri secara mudah.</p>
    <form action="{{ route('opac.index') }}" method="GET">
        <div class="search-box-container">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci judul, pengarang, subjek atau ISBN..." class="search-input" autofocus autocomplete="off">
            <button type="submit" class="search-btn">Cari Buku</button>
        </div>
    </form>
</div>

<div class="opac-layout-grid">
    <div class="results-container">
        @if($results->isEmpty())
            <div style="text-align: center; color: #64748b; padding: 4rem 0; background: #f8fafc; border-radius: 12px; border: 1px dashed #e2e8f0;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8; margin-bottom: 1rem;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                <p style="font-size: 1.1rem; font-weight: 500; color: #475569;">Tidak ada koleksi yang cocok dengan kata kunci Anda.</p>
            </div>
        @else
            <div class="books-grid">
                @foreach($results as $book)
                    <div class="book-card">
                        <div>
                            <h3><a href="{{ route('opac.show', $book->biblio_id) }}">{{ $book->title }}</a></h3>
                            @if($book->authors->isNotEmpty())
                                <p style="color: var(--primary); font-size: 0.92rem; margin: -0.25rem 0 0.85rem 0; font-weight: 500;">
                                    {{ $book->authors->pluck('author_name')->join(', ') }}
                                </p>
                            @endif
                            <p class="book-info" style="color: #64748b;">
                                @if($book->classification)
                                    <span style="font-family: monospace; color: #475569; background: #f1f5f9; padding: 0.15rem 0.4rem; border-radius: 4px;">{{ $book->classification }}</span>
                                    <span style="margin: 0 0.25rem;">•</span>
                                @endif
                                @if($book->isbn_issn)
                                    <span>ISBN: {{ $book->isbn_issn }}</span>
                                    <span style="margin: 0 0.25rem;">•</span>
                                @endif
                                <span>Tahun {{ $book->publish_year ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="book-meta">
                            <span class="badge">{{ $book->gmd->gmd_name ?? 'Buku' }}</span>
                            <a href="{{ route('opac.show', $book->biblio_id) }}" class="btn-detail">
                                Detail 
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                {{ $results->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- SIDEBAR INFO BLOCK -->
    <div class="opac-sidebar">
        <!-- Widget Pengumuman / Info Terbaru -->
        @if(isset($promotions) && $promotions->isNotEmpty())
            <div class="sidebar-widget">
                <h3 class="widget-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0M4 17h16M7 17v-6a5 5 0 0 1 10 0v6"/></svg>
                    Informasi & Pengumuman
                </h3>
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($promotions as $promo)
                        <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                            <h4 style="margin: 0 0 0.35rem 0; font-size: 0.9rem; font-weight: 700; color: var(--primary);">{{ $promo->title }}</h4>
                            <p style="margin: 0; font-size: 0.8rem; color: #64748b; line-height: 1.45;">{{ Str::limit($promo->content, 120) }}</p>
                            <span style="font-size: 0.7rem; color: #94a3b8; display: block; margin-top: 0.4rem;">
                                {{ $promo->publish_date ? $promo->publish_date->format('d M Y') : '' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Widget Jam Layanan -->
        <div class="sidebar-widget">
            <h3 class="widget-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Jam Operasional
            </h3>
            
            <div id="status-operasional-container">
                <!-- Javascript will inject status here dynamically based on actual server time -->
            </div>

            <div style="font-size: 0.88rem; color: #64748b; display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                <div style="display:flex; justify-content:space-between;">
                    <span>Senin – Jumat</span>
                    <span style="color: #334155; font-weight: 600;">08:00 – 20:00</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Sabtu</span>
                    <span style="color: #94a3b8;">Tutup</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Minggu</span>
                    <span style="color: #94a3b8;">Tutup</span>
                </div>
            </div>
        </div>

        <!-- Widget Statistik Real-time -->
        <div class="sidebar-widget">
            <h3 class="widget-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                Statistik Koleksi
            </h3>
            <div style="display: flex; flex-direction: column;">
                <div class="stat-row">
                    <span style="color: #64748b; font-size: 0.88rem;">Total Judul Buku</span>
                    <span class="stat-number">{{ number_format($totalTitles ?? 0) }}</span>
                </div>
                <div class="stat-row">
                    <span style="color: #64748b; font-size: 0.88rem;">Total Eksemplar</span>
                    <span class="stat-number">{{ number_format($totalItems ?? 0) }}</span>
                </div>
                <div class="stat-row">
                    <span style="color: #64748b; font-size: 0.88rem;">Sedang Dipinjam</span>
                    <span class="stat-number" style="color: #ef4444;">{{ number_format($activeLoans ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateOpeningStatus() {
        const now = new Date();
        const day = now.getDay();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const currentTimeDecimal = hours + (minutes / 60);

        const container = document.getElementById('status-operasional-container');
        if (!container) return;

        let isOpen = false;
        if (day >= 1 && day <= 5) {
            if (currentTimeDecimal >= 8.0 && currentTimeDecimal < 20.0) {
                isOpen = true;
            }
        }

        if (isOpen) {
            container.innerHTML = `
                <div class="hours-status status-open" style="background: rgba(20, 184, 166, 0.1); color: #0d9488; border: 1px solid rgba(20, 184, 166, 0.2);">
                    <span style="display:inline-block; width:8px; height:8px; background:#0d9488; border-radius:50%; animation: pulse 1.5s infinite;"></span>
                    Buka Sekarang
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="hours-status status-closed" style="background: rgba(239, 68, 68, 0.05); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15);">
                    <span style="display:inline-block; width:8px; height:8px; background:#ef4444; border-radius:50%;"></span>
                    Sudah Tutup
                </div>
            `;
        }
    }
    updateOpeningStatus();
});
</script>
@endsection
