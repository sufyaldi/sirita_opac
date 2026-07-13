@extends('layouts.admin')

@section('title', 'Ubah Bibliografi - SIRITA Admin')
@section('header_title', 'Ubah Katalog Bibliografi')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 3rem;
        border-radius: 20px;
        backdrop-filter: blur(12px);
        max-width: 800px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border-radius: 10px;
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid var(--border-color);
        color: white;
        outline: none;
        box-sizing: border-box;
        font-family: inherit;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .btn-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .btn-secondary {
        background: transparent;
        border: 1px solid var(--border-color);
        color: #94a3b8;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
    }
</style>

<div class="glass-card">
    <form action="{{ route('admin.bibliography.update', $bibliography->biblio_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title" class="form-label">Judul Buku / Dokumen</label>
            <input type="text" id="title" name="title" value="{{ old('title', $bibliography->title) }}" class="form-control" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="isbn_issn" class="form-label">ISBN / ISSN</label>
                <input type="text" id="isbn_issn" name="isbn_issn" value="{{ old('isbn_issn', $bibliography->isbn_issn) }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="publish_year" class="form-label">Tahun Terbit</label>
                <input type="text" id="publish_year" name="publish_year" value="{{ old('publish_year', $bibliography->publish_year) }}" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="edition" class="form-label">Edisi / Cetakan</label>
                <input type="text" id="edition" name="edition" value="{{ old('edition', $bibliography->edition) }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="call_number" class="form-label">No. Panggil</label>
                <input type="text" id="call_number" name="call_number" value="{{ old('call_number', $bibliography->call_number) }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="classification" class="form-label">Klasifikasi</label>
            <input type="text" id="classification" name="classification" value="{{ old('classification', $bibliography->classification) }}" class="form-control">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="cover_image" class="form-label">Gambar Sampul (Cover Image)</label>
                <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                @if($bibliography->image)
                    <span style="font-size: 0.85rem; color: #34d399;">Sampul aktif: {{ $bibliography->image }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="attachment" class="form-label">Lampiran Dokumen / E-Book (PDF/EPUB)</label>
                <input type="file" id="attachment" name="attachment" class="form-control">
                @if($bibliography->attachments->isNotEmpty())
                    <span style="font-size: 0.85rem; color: #34d399;">Berkas terlampir: {{ $bibliography->attachments->first()->file_title }}</span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Catatan / Abstrak Singkat</label>
            <textarea id="notes" name="notes" rows="4" class="form-control">{{ old('notes', $bibliography->notes) }}</textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-primary">Perbarui Katalog</button>
            <a href="{{ route('admin.bibliography.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
