@extends('opac.templates.classic.layout')

@section('title', $book->title . ' - Detail Buku')

@section('content')
<style>
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #94a3b8;
        text-decoration: none;
        margin-bottom: 2rem;
        font-weight: 500;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #2dd4bf;
    }

    .detail-container {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 3.5rem;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 28px;
        padding: 3rem;
        backdrop-filter: blur(12px);
    }

    @media (max-width: 768px) {
        .detail-container {
            grid-template-columns: 1fr;
            padding: 2rem;
            gap: 2rem;
        }
    }

    .book-cover-container {
        text-align: center;
    }

    .book-cover-placeholder {
        width: 100%;
        aspect-ratio: 2/3;
        background: linear-gradient(135deg, #111827 0%, #030712 100%);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #475569;
        font-weight: 600;
        box-shadow: 0 20px 30px -10px rgba(0,0,0,0.5);
    }

    .book-details h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2.3rem;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.03em;
        color: #f1f5f9;
    }

    .specs-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
    }

    .specs-table th, .specs-table td {
        padding: 0.85rem 0;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .specs-table th {
        color: #64748b;
        font-weight: 500;
        width: 180px;
        font-size: 0.9rem;
    }

    .specs-table td {
        color: #cbd5e1;
        font-size: 0.95rem;
    }

    .availability-section {
        margin-top: 3rem;
        background: rgba(15, 23, 42, 0.3);
        border-radius: 20px;
        padding: 1.5rem;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .availability-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        color: #f1f5f9;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 99px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-available {
        background: rgba(20, 184, 166, 0.15);
        color: #2dd4bf;
        border: 1px solid rgba(20, 184, 166, 0.3);
    }

    .status-lent {
        background: rgba(239, 68, 68, 0.12);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
</style>

<a href="{{ route('opac.index') }}" class="back-link">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
    Kembali ke Pencarian
</a>

<div class="detail-container">
    <div class="book-cover-container">
        @if($book->image)
            <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" style="width: 100%; border-radius: 20px; box-shadow: 0 20px 30px -10px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.05);">
        @else
            <div class="book-cover-placeholder">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 3.5rem; height: 3.5rem; margin-bottom: 0.75rem; color: #475569;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
                <span style="font-size: 0.88rem; text-transform: uppercase; letter-spacing: 0.05em;">Tanpa Sampul</span>
            </div>
        @endif
    </div>

    <div class="book-details">
        <h1 style="color: white;">{{ $book->title }}</h1>
        <span class="badge" style="margin-bottom: 1.5rem;">{{ $book->gmd->gmd_name ?? 'Text' }}</span>

        <table class="specs-table">
            <tbody>
                @if($book->authors->isNotEmpty())
                <tr>
                    <th>Pengarang</th>
                    <td style="color: #2dd4bf; font-weight: 600;">{{ $book->authors->pluck('author_name')->join(', ') }}</td>
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
                    <td style="font-family: monospace; color: #7dd3fc;">{{ $book->call_number ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Klasifikasi</th>
                    <td style="font-family: monospace;">{{ $book->classification ?? '-' }}</td>
                </tr>
                @if($book->attachments->isNotEmpty())
                <tr>
                    <th>Lampiran / E-Book</th>
                    <td>
                        @foreach($book->attachments as $attach)
                            <a href="{{ asset('storage/' . $attach->file_dir) }}" target="_blank" style="color: #2dd4bf; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(13, 148, 136, 0.1); border: 1px solid rgba(13, 148, 136, 0.2); padding: 0.35rem 0.75rem; border-radius: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 1rem; height: 1rem;">
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
                    <th>Catatan / Abstrak</th>
                    <td style="line-height: 1.6; color: #94a3b8;">{{ $book->notes }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="availability-section">
            <div class="availability-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #2dd4bf;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Informasi Ketersediaan Eksemplar
            </div>
            @if($book->items->isEmpty())
                <p style="color: #64748b; font-size: 0.9rem; margin: 0;">Belum ada data eksemplar fisik untuk buku ini.</p>
            @else
                <table class="specs-table" style="margin-top: 0;">
                    <thead>
                        <tr>
                            <th style="font-size: 0.75rem; padding-bottom: 0.5rem; text-transform: uppercase;">Kode Barcode</th>
                            <th style="font-size: 0.75rem; padding-bottom: 0.5rem; text-transform: uppercase;">Lokasi Rak</th>
                            <th style="font-size: 0.75rem; padding-bottom: 0.5rem; text-transform: uppercase; text-align: right;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($book->items as $item)
                            <tr>
                                <td><code style="color: #f1f5f9; background: rgba(255,255,255,0.04); padding: 0.2rem 0.5rem; border-radius: 6px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.02);">{{ $item->item_code }}</code></td>
                                <td style="font-size: 0.9rem;">{{ $item->location_id ?? 'Rak Utama' }}</td>
                                <td style="text-align: right;">
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
