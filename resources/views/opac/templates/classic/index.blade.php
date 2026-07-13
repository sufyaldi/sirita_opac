@extends('layouts.opac')

@section('title', 'Cari Buku - SIRITA OPAC')

@section('content')
<style>
    .search-hero {
        text-align: center;
        margin-bottom: 3.5rem;
        padding: 4rem 2rem;
        background: radial-gradient(circle at top, rgba(13, 148, 136, 0.12) 0%, transparent 70%);
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .search-hero h1 {
        font-size: 3.2rem;
        margin-bottom: 0.75rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        background: linear-gradient(135deg, #f8fafc 30%, #0d9488 90%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .search-hero p {
        color: #94a3b8;
        font-size: 1.15rem;
        margin-bottom: 2.5rem;
        font-weight: 400;
    }

    .search-box-container {
        max-width: 650px;
        margin: 0 auto;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 1.25rem 1.75rem;
        font-size: 1.15rem;
        border-radius: 99px;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--border-color);
        color: white;
        outline: none;
        box-sizing: border-box;
        transition: all 0.3s ease;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 25px rgba(13, 148, 136, 0.3);
        background: rgba(15, 23, 42, 0.85);
    }

    .search-btn {
        position: absolute;
        right: 8px;
        top: 8px;
        bottom: 8px;
        background: linear-gradient(135deg, #0d9488, #2dd4bf);
        border: none;
        color: white;
        padding: 0 2rem;
        border-radius: 99px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
    }

    .search-btn:hover {
        opacity: 0.95;
        box-shadow: 0 4px 20px rgba(13, 148, 136, 0.4);
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 2rem;
    }

    .book-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.75rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }

    .book-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(800px circle at var(--x, 0px) var(--y, 0px), rgba(13, 148, 136, 0.06), transparent 40%);
        z-index: 1;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .book-card:hover::before {
        opacity: 1;
    }

    .book-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        border-color: rgba(13, 148, 136, 0.4);
    }

    .book-card h3 {
        margin: 0 0 0.6rem 0;
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1.4;
        z-index: 2;
        position: relative;
    }

    .book-card h3 a {
        color: #f1f5f9;
        text-decoration: none;
        transition: color 0.2s;
    }

    .book-card h3 a:hover {
        color: #2dd4bf;
    }

    .book-info {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 1.5rem;
        z-index: 2;
        position: relative;
    }

    .book-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.25rem;
        margin-top: auto;
        z-index: 2;
        position: relative;
    }

    .badge {
        background: rgba(13, 148, 136, 0.12);
        color: #2dd4bf;
        padding: 0.3rem 0.85rem;
        border-radius: 99px;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        border: 1px solid rgba(13, 148, 136, 0.2);
    }

    .btn-detail {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        transition: color 0.2s;
    }

    .btn-detail:hover {
        color: #2dd4bf;
    }

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
        color: #475569;
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
        border-radius: 10px;
        border: 1px solid var(--border-color) !important;
        background: rgba(15, 23, 42, 0.4) !important;
        color: #cbd5e1 !important;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s;
    }

    .pagination nav a:hover {
        border-color: var(--primary) !important;
        color: white !important;
        background: rgba(13, 148, 136, 0.15) !important;
    }

    .pagination nav span[aria-current="page"] span {
        background: linear-gradient(135deg, #0d9488, #0f766e) !important;
        border-color: var(--primary) !important;
        color: white !important;
        font-weight: 700;
    }

    .pagination svg {
        width: 1rem !important;
        height: 1rem !important;
        display: inline-block;
        vertical-align: middle;
    }

    .pagination span[style*="flex-1"] {
        display: none !important;
    }

    /* 2-Column Layout */
    .opac-layout-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 2.5rem;
        align-items: start;
    }

    @media (max-width: 992px) {
        .opac-layout-grid {
            grid-template-columns: 1fr;
        }
    }

    .opac-sidebar {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .sidebar-widget {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
    }

    .widget-title {
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin: 0 0 1.25rem 0;
        color: #cbd5e1;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        padding-bottom: 0.75rem;
    }

    /* Stat Styles */
    .stat-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        border-bottom: 1px dashed rgba(255,255,255,0.05);
    }
    .stat-row:last-child {
        border-bottom: none;
    }
    .stat-number {
        font-size: 1.2rem;
        font-weight: 800;
        color: #2dd4bf;
    }

    /* Hours styles */
    .hours-status {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.3rem 0.75rem;
        border-radius: 99px;
        font-size: 0.8rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    .status-open {
        background: rgba(20, 184, 166, 0.15);
        color: #2dd4bf;
        border: 1px solid rgba(20, 184, 166, 0.25);
    }
    .status-closed {
        background: rgba(239, 68, 68, 0.12);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
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
            <div style="text-align: center; color: #64748b; padding: 4rem 0; background: rgba(30, 41, 59, 0.2); border-radius: 20px; border: 1px dashed var(--border-color);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: #475569; margin-bottom: 1rem;"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line><line x1="11" y1="8" x2="11" y2="14"></line><line x1="8" y1="11" x2="14" y2="11"></line></svg>
                <p style="font-size: 1.1rem; font-weight: 500;">Tidak ada koleksi yang cocok dengan kata kunci Anda.</p>
                <p style="font-size: 0.88rem; color: #475569; margin-top: 0.25rem;">Coba periksa ejaan atau cari menggunakan kata kunci yang lebih umum.</p>
            </div>
        @else
            <div class="books-grid">
                @foreach($results as $book)
                    <div class="book-card" onmousemove="this.style.setProperty('--x', event.offsetX + 'px'); this.style.setProperty('--y', event.offsetY + 'px')">
                        <div>
                            <h3><a href="{{ route('opac.show', $book->biblio_id) }}">{{ $book->title }}</a></h3>
                            @if($book->authors->isNotEmpty())
                                <p style="color: #2dd4bf; font-size: 0.92rem; margin: -0.25rem 0 0.85rem 0; font-weight: 500;">
                                    {{ $book->authors->pluck('author_name')->join(', ') }}
                                </p>
                            @endif
                            <p class="book-info">
                                @if($book->classification)
                                    <span style="font-family: monospace; color: #94a3b8; background: rgba(255,255,255,0.05); padding: 0.15rem 0.4rem; border-radius: 4px;">{{ $book->classification }}</span>
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
                        <div style="border-bottom: 1px solid rgba(255,255,255,0.03); padding-bottom: 0.75rem;">
                            <h4 style="margin: 0 0 0.35rem 0; font-size: 0.9rem; font-weight: 700; color: #2dd4bf;">{{ $promo->title }}</h4>
                            <p style="margin: 0; font-size: 0.8rem; color: #64748b; line-height: 1.45;">{{ Str::limit($promo->content, 120) }}</p>
                            <span style="font-size: 0.7rem; color: #475569; display: block; margin-top: 0.4rem;">
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

            <div style="font-size: 0.88rem; color: #94a3b8; display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.5rem;">
                <div style="display:flex; justify-content:space-between;">
                    <span>Senin – Jumat</span>
                    <span style="color: #cbd5e1; font-weight: 600;">08:00 – 20:00</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Sabtu</span>
                    <span style="color: #64748b;">Tutup</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span>Minggu</span>
                    <span style="color: #64748b;">Tutup</span>
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
                    <span style="color: #94a3b8; font-size: 0.88rem;">Total Judul Buku</span>
                    <span class="stat-number">{{ number_format($totalTitles ?? 0) }}</span>
                </div>
                <div class="stat-row">
                    <span style="color: #94a3b8; font-size: 0.88rem;">Total Eksemplar</span>
                    <span class="stat-number" style="color: #2dd4bf;">{{ number_format($totalItems ?? 0) }}</span>
                </div>
                <div class="stat-row">
                    <span style="color: #94a3b8; font-size: 0.88rem;">Sedang Dipinjam</span>
                    <span class="stat-number" style="color: #f87171;">{{ number_format($activeLoans ?? 0) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dynamic opening status calculation based on current server-synced local time
    function updateOpeningStatus() {
        const now = new Date();
        const day = now.getDay(); // 0 = Minggu, 6 = Sabtu, 1-5 = Senin-Jumat
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const currentTimeDecimal = hours + (minutes / 60);

        const container = document.getElementById('status-operasional-container');
        if (!container) return;

        let isOpen = false;
        if (day >= 1 && day <= 5) { // Senin - Jumat
            if (currentTimeDecimal >= 8.0 && currentTimeDecimal < 20.0) {
                isOpen = true;
            }
        }

        if (isOpen) {
            container.innerHTML = `
                <div class="hours-status status-open">
                    <span style="display:inline-block; width:8px; height:8px; background:#2dd4bf; border-radius:50%; animation: pulse 1.5s infinite;"></span>
                    Buka Sekarang
                </div>
            `;
        } else {
            container.innerHTML = `
                <div class="hours-status status-closed">
                    <span style="display:inline-block; width:8px; height:8px; background:#f87171; border-radius:50%;"></span>
                    Sudah Tutup
                </div>
            `;
        }
    }

    // Add pulse keyframes dynamically if not present
    if (!document.getElementById('pulse-style')) {
        const style = document.createElement('style');
        style.id = 'pulse-style';
        style.innerHTML = `@keyframes pulse { 0% { opacity: 0.4; } 50% { opacity: 1; } 100% { opacity: 0.4; } }`;
        document.head.appendChild(style);
    }

    updateOpeningStatus();
});
</script>
@endsection

