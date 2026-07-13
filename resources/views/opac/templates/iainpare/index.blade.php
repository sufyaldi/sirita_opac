@extends('opac.templates.iainpare.layout')

@section('title', 'Cari Koleksi - OPAC IAIN Parepare')

@section('content')
<style>
    :root {
        --bg-color: #f8fafc;
        --text-color: #1e293b;
        --primary: #15803d;
        --primary-hover: #166534;
        --accent: #d97706;
        --card-bg: #ffffff;
        --border-color: #e2e8f0;
    }

    body {
        background-color: var(--bg-color) !important;
        color: var(--text-color) !important;
    }

    .search-hero {
        text-align: center;
        margin-bottom: 2.5rem;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, rgba(21, 128, 61, 0.05) 0%, rgba(217, 119, 6, 0.03) 100%);
        border-radius: 16px;
        border: 1px solid rgba(21, 128, 61, 0.1);
        background-color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .search-hero h1 {
        font-size: 2.6rem;
        margin-bottom: 0.75rem;
        font-weight: 800;
        color: #14532d;
    }

    .search-hero p {
        color: #4b5563;
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }

    .search-box-container {
        max-width: 650px;
        margin: 0 auto;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 1.1rem 1.5rem;
        font-size: 1.1rem;
        border-radius: 8px;
        background: #ffffff;
        border: 2px solid #cbd5e1;
        color: #1e293b;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s ease;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.04);
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(21, 128, 61, 0.2);
    }

    .search-btn {
        position: absolute;
        right: 6px;
        top: 6px;
        bottom: 6px;
        background: var(--primary);
        border: none;
        color: white;
        padding: 0 1.75rem;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }

    .search-btn:hover {
        background: var(--primary-hover);
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.75rem;
    }

    .book-card {
        background: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.2s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    }

    .book-card::before {
        display: none !important;
    }

    .book-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -3px rgba(0,0,0,0.08);
        border-color: var(--primary) !important;
    }

    .book-card h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.15rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .book-card h3 a {
        color: #1e293b;
        text-decoration: none;
        transition: color 0.2s;
    }

    .book-card h3 a:hover {
        color: var(--primary);
    }

    .book-info {
        color: #64748b;
        font-size: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .book-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--border-color);
        padding-top: 1rem;
        margin-top: auto;
    }

    .badge {
        background: rgba(21, 128, 61, 0.08);
        color: var(--primary);
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        border: 1px solid rgba(21, 128, 61, 0.12);
    }

    .btn-detail {
        color: #64748b;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: color 0.2s;
    }

    .btn-detail:hover {
        color: var(--primary);
    }

    /* Sidebar Widgets */
    .sidebar-widget {
        background: #ffffff !important;
        border: 1px solid var(--border-color) !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05) !important;
        border-radius: 12px !important;
        padding: 1.25rem !important;
    }

    .widget-title {
        color: #14532d !important;
        border-bottom: 2px solid var(--accent) !important;
        padding-bottom: 0.5rem !important;
        font-size: 0.95rem !important;
        font-weight: 700 !important;
    }

    .stat-row {
        border-bottom: 1px dashed #e2e8f0 !important;
        padding: 0.6rem 0 !important;
    }

    .stat-number {
        color: var(--primary) !important;
        font-size: 1.15rem !important;
        font-weight: 700;
    }

    .hours-status {
        font-size: 0.75rem !important;
        border-radius: 4px !important;
    }

    .status-open {
        background: rgba(16, 185, 129, 0.1) !important;
        color: #10b981 !important;
        border: 1px solid rgba(16, 185, 129, 0.2) !important;
    }

    .status-closed {
        background: rgba(239, 68, 68, 0.08) !important;
        color: #ef4444 !important;
        border: 1px solid rgba(239, 68, 68, 0.15) !important;
    }

    /* Fix Pagination Giant Icons */
    .pagination {
        margin-top: 3.5rem;
        display: flex;
        justify-content: center;
        width: 100%;
    }
    .pagination nav {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1.25rem;
        width: 100%;
    }
    .pagination nav > div:first-child {
        display: none !important;
    }
    .pagination nav > div:last-child {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    .pagination nav p {
        color: #64748b;
        font-size: 0.85rem;
        margin: 0;
    }
    .pagination nav span[aria-current="page"] span,
    .pagination nav a,
    .pagination nav span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        border: 1px solid var(--border-color) !important;
        background: #ffffff !important;
        color: #475569 !important;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .pagination nav a:hover {
        border-color: var(--primary) !important;
        color: white !important;
        background: var(--primary) !important;
    }
    .pagination nav span[aria-current="page"] span {
        background: var(--primary) !important;
        border-color: var(--primary) !important;
        color: white !important;
        font-weight: 700;
    }
    .pagination svg {
        width: 1.25rem !important;
        height: 1.25rem !important;
        display: inline-block;
        vertical-align: middle;
    }
    .pagination span[style*="flex-1"] {
        display: none !important;
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
            <div style="text-align: center; color: #64748b; padding: 4rem 0; background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8; margin-bottom: 1rem;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                <p style="font-size: 1.1rem; font-weight: 600; color: #475569;">Tidak ada koleksi yang cocok dengan kata kunci Anda.</p>
            </div>
        @else
            <div class="books-grid">
                @foreach($results as $book)
                    <div class="book-card">
                        <div>
                            <h3><a href="{{ route('opac.show', $book->biblio_id) }}">{{ $book->title }}</a></h3>
                            @if($book->authors->isNotEmpty())
                                <p style="color: var(--primary); font-size: 0.92rem; margin: -0.25rem 0 0.85rem 0; font-weight: 600;">
                                    {{ $book->authors->pluck('author_name')->join(', ') }}
                                </p>
                            @endif
                            <p class="book-info">
                                @if($book->classification)
                                    <span style="font-family: monospace; color: #475569; background: #edf2f7; padding: 0.15rem 0.4rem; border-radius: 4px; font-weight: 600;">{{ $book->classification }}</span>
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
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem;">
                    @foreach($promotions as $promo)
                        <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">
                            <h4 style="margin: 0 0 0.35rem 0; font-size: 0.9rem; font-weight: 700; color: var(--primary);">{{ $promo->title }}</h4>
                            <p style="margin: 0; font-size: 0.8rem; color: #475569; line-height: 1.45;">{{ Str::limit($promo->content, 120) }}</p>
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
            
            <div id="status-operasional-container" style="margin-top: 1rem;">
                <!-- Javascript will inject status here dynamically based on actual server time -->
            </div>

            <div style="font-size: 0.88rem; color: #475569; display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                <div style="display:flex; justify-content:space-between;">
                    <span>Senin – Jumat</span>
                    <span style="color: #1e293b; font-weight: 600;">08:00 – 20:00</span>
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
            <div style="display: flex; flex-direction: column; margin-top: 1rem;">
                <div class="stat-row">
                    <span style="color: #475569; font-size: 0.88rem;">Total Judul Buku</span>
                    <span class="stat-number">{{ number_format($totalTitles ?? 0) }}</span>
                </div>
                <div class="stat-row">
                    <span style="color: #475569; font-size: 0.88rem;">Total Eksemplar</span>
                    <span class="stat-number">{{ number_format($totalItems ?? 0) }}</span>
                </div>
                <div class="stat-row">
                    <span style="color: #475569; font-size: 0.88rem;">Sedang Dipinjam</span>
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
                <div class="hours-status status-open">
                    <span style="display:inline-block; width:8px; height:8px; background:#10b981; border-radius:50%; animation: pulse 1.5s infinite;"></span>
                    Buka Sekarang
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="hours-status status-closed">
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
