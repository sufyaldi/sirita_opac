@extends('layouts.admin')

@section('title', 'Tambah Bibliografi - SIRITA Admin')
@section('header_title', 'Tambah Bibliografi Baru')

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
    <form action="{{ route('admin.bibliography.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title" class="form-label">Judul Buku / Dokumen</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Pemrograman Modern Laravel 11" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="isbn_issn" class="form-label">ISBN / ISSN</label>
                <input type="text" id="isbn_issn" name="isbn_issn" class="form-control" placeholder="Contoh: 978-602-876-123-4">
            </div>
            <div class="form-group">
                <label for="publish_year" class="form-label">Tahun Terbit</label>
                <input type="text" id="publish_year" name="publish_year" class="form-control" placeholder="Contoh: 2026">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="edition" class="form-label">Edisi / Cetakan</label>
                <input type="text" id="edition" name="edition" class="form-control" placeholder="Contoh: Edisi 2">
            </div>
            <div class="form-group">
                <label for="call_number" class="form-label">No. Panggil</label>
                <input type="text" id="call_number" name="call_number" class="form-control" placeholder="Contoh: 005.2 SHA p">
            </div>
        </div>

        <div class="form-group">
            <label for="classification" class="form-label">Klasifikasi</label>
            <input type="text" id="classification" name="classification" class="form-control" placeholder="Contoh: 005.2">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="cover_image" class="form-label">Gambar Sampul (Cover Image)</label>
                <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
            </div>
            <div class="form-group">
                <label for="attachment" class="form-label">Lampiran Dokumen / E-Book (PDF/EPUB)</label>
                <input type="file" id="attachment" name="attachment" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Catatan / Abstrak Singkat</label>
            <textarea id="notes" name="notes" rows="4" class="form-control" placeholder="Masukkan deskripsi ringkas isi buku..."></textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-primary">Simpan Katalog</button>
            <a href="{{ route('admin.bibliography.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
