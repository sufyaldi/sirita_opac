@extends('layouts.opac')

@section('title', 'Cari Buku - SIRITA OPAC')

@section('content')
<style>
    .search-hero {
        text-align: center;
        margin-bottom: 4rem;
    }

    .search-hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        font-weight: 700;
        letter-spacing: -0.05em;
    }

    .search-hero p {
        color: #94a3b8;
        font-size: 1.2rem;
        margin-bottom: 2rem;
    }

    .search-box-container {
        max-width: 600px;
        margin: 0 auto;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 1.2rem 1.5rem;
        font-size: 1.1rem;
        border-radius: 9999px;
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid var(--border-color);
        color: white;
        outline: none;
        box-sizing: border-box;
        transition: all 0.3s ease;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.4);
        background: rgba(30, 41, 59, 0.8);
    }

    .search-btn {
        position: absolute;
        right: 8px;
        top: 8px;
        bottom: 8px;
        background: var(--primary);
        border: none;
        color: white;
        padding: 0 1.5rem;
        border-radius: 9999px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .search-btn:hover {
        background: var(--primary-hover);
    }

    .books-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .book-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        border-color: rgba(99, 102, 241, 0.3);
    }

    .book-card h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.25rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .book-card h3 a {
        color: white;
        text-decoration: none;
    }

    .book-card h3 a:hover {
        color: var(--primary);
    }

    .book-info {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
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
        background: rgba(99, 102, 241, 0.15);
        color: #a5b4fc;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .pagination {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .pagination a, .pagination span {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: white;
        text-decoration: none;
    }

    .pagination .active {
        background: var(--primary);
        border-color: var(--primary);
    }
</style>

<div class="search-hero">
    <h1>Cari Koleksi Perpustakaan</h1>
    <p>Telusuri ribuan judul buku, artikel, dan terbitan berseri secara instan.</p>
    <form action="{{ route('opac.index') }}" method="GET">
        <div class="search-box-container">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik kata kunci judul, pengarang, atau ISBN..." class="search-input" autofocus>
            <button type="submit" class="search-btn">Cari</button>
        </div>
    </form>
</div>

<div class="results-container">
    @if($results->isEmpty())
        <div style="text-align: center; color: #64748b; padding: 4rem 0;">
            <p style="font-size: 1.2rem;">Tidak ada koleksi yang cocok dengan kata kunci Anda.</p>
        </div>
    @else
        <div class="books-grid">
            @foreach($results as $book)
                <div class="book-card">
                    <div>
                        <h3><a href="{{ route('opac.show', $book->biblio_id) }}">{{ $book->title }}</a></h3>
                        @if($book->authors->isNotEmpty())
                            <p style="color: #a5b4fc; font-size: 0.95rem; margin: -0.25rem 0 0.75rem 0; font-weight: 500;">
                                {{ $book->authors->pluck('author_name')->join(', ') }}
                            </p>
                        @endif
                        <p class="book-info">
                            @if($book->isbn_issn)
                                <span>ISBN: {{ $book->isbn_issn }}</span> • 
                            @endif
                            <span>Tahun: {{ $book->publish_year ?? '-' }}</span>
                        </p>
                    </div>
                    <div class="book-meta">
                        <span class="badge">{{ $book->gmd->gmd_name ?? 'Buku' }}</span>
                        <a href="{{ route('opac.show', $book->biblio_id) }}" style="color: #818cf8; text-decoration: none; font-size: 0.9rem; font-weight: 500;">Detail &rarr;</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            {{ $results->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
