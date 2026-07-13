@extends('layouts.opac')

@section('title', $book->title . ' - Detail Buku')

@section('content')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        color: #94a3b8;
        text-decoration: none;
        margin-bottom: 2rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: white;
    }

    .detail-container {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 3rem;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 24px;
        padding: 3rem;
        backdrop-filter: blur(10px);
    }

    @media (max-width: 768px) {
        .detail-container {
            grid-template-columns: 1fr;
            padding: 2rem;
        }
    }

    .book-cover-container {
        text-align: center;
    }

    .book-cover-placeholder {
        width: 100%;
        aspect-ratio: 2/3;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #475569;
        font-weight: 600;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
    }

    .book-details h1 {
        margin: 0 0 1.5rem 0;
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .specs-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
    }

    .specs-table th, .specs-table td {
        padding: 1rem 0;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .specs-table th {
        color: #94a3b8;
        font-weight: 500;
        width: 200px;
    }

    .specs-table td {
        color: white;
    }

    .availability-section {
        margin-top: 3rem;
    }

    .availability-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-available {
        background: rgba(16, 185, 129, 0.15);
        color: #34d399;
    }

    .status-lent {
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }
</style>

<a href="{{ route('opac.index') }}" class="back-link">&larr; Kembali ke Pencarian</a>

<div class="detail-container">
    <div class="book-cover-container">
        @if($book->image)
            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" style="width: 100%; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);">
        @else
            <div class="book-cover-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 4rem; height: 4rem; margin-bottom: 1rem;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <span>No Cover</span>
            </div>
        @endif
    </div>

    <div class="book-details">
        <h1>{{ $book->title }}</h1>
        <span class="badge">{{ $book->gmd->gmd_name ?? 'Text' }}</span>

        <table class="specs-table">
            <tbody>
                @if($book->authors->isNotEmpty())
                <tr>
                    <th>Pengarang</th>
                    <td>{{ $book->authors->pluck('author_name')->join(', ') }}</td>
                </tr>
                @endif
                <tr>
                    <th>ISBN / ISSN</th>
                    <td>{{ $book->isbn_issn ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Penerbit</th>
                    <td>{{ $book->publisher->publisher_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tahun Terbit</th>
                    <td>{{ $book->publish_year ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Edisi</th>
                    <td>{{ $book->edition ?? '-' }}</td>
                </tr>
                <tr>
                    <th>No. Panggil</th>
                    <td>{{ $book->call_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Klasifikasi</th>
                    <td>{{ $book->classification ?? '-' }}</td>
                </tr>
                @if($book->attachments->isNotEmpty())
                <tr>
                    <th>Lampiran / E-Book</th>
                    <td>
                        @foreach($book->attachments as $attach)
                            <a href="{{ asset('storage/' . $attach->file_dir) }}" target="_blank" style="color: #6366f1; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 1.2rem; height: 1.2rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                {{ $attach->file_title }}
                            </a>
                        @endforeach
                    </td>
                </tr>
                @endif
                @if($book->notes)
                <tr>
                    <th>Catatan/Abstrak</th>
                    <td>{{ $book->notes }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="availability-section">
            <div class="availability-title">Ketersediaan Buku</div>
            @if($book->items->isEmpty())
                <p style="color: #64748b;">Belum ada data eksemplar fisik untuk buku ini.</p>
            @else
                <table class="specs-table" style="margin-top: 1rem;">
                    <thead>
                        <tr>
                            <th style="width: 150px;">Kode Barcode</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book->items as $item)
                            <tr>
                                <td><code>{{ $item->item_code }}</code></td>
                                <td>{{ $item->location_id ?? 'Rak Utama' }}</td>
                                <td>
                                    @php
                                        $isLent = \App\Models\Loan::where('item_code', $item->item_code)->where('is_return', 0)->exists();
                                    @endphp
                                    @if($isLent)
                                        <span class="status-pill status-lent">Dipinjam</span>
                                    @else
                                        <span class="status-pill status-available">Tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
