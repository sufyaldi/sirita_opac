@extends('layouts.admin')

@section('title', 'Edit Halaman OPAC - SIRITA Admin')
@section('header_title', 'Edit Halaman Dinamis')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2.5rem;
        border-radius: 20px;
        backdrop-filter: blur(12px);
        max-width: 900px;
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
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .btn-submit {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: var(--primary-hover);
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid var(--border-color);
        color: #94a3b8;
        padding: 0.8rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.03);
        color: white;
    }
</style>

<!-- Load Rich Text Editor (CKEditor 5) -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<div class="glass-card">
    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title" class="form-label">Judul Halaman</label>
            <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Sejarah & Visi Misi" required autocomplete="off" value="{{ old('title', $page->title) }}">
        </div>

        <div class="form-group">
            <label for="content_editor" class="form-label">Konten Halaman</label>
            <div style="color: #000000; min-height: 300px;">
                <textarea id="content_editor" name="content">{{ old('content', $page->content) }}</textarea>
            </div>
        </div>

        <div class="form-group">
            <label for="is_active" class="form-label">Status Publikasi</label>
            <select id="is_active" name="is_active" class="form-control" required style="background: #1e293b;">
                <option value="1" {{ $page->is_active === 1 ? 'selected' : '' }}>Aktif / Terbitkan</option>
                <option value="0" {{ $page->is_active === 0 ? 'selected' : '' }}>Draft / Simpan Saja</option>
            </select>
        </div>

        <div style="display: flex; gap: 1rem; margin-top: 2rem;">
            <button type="submit" class="btn-submit">Simpan Halaman</button>
            <a href="{{ route('admin.pages.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

<script>
    ClassicEditor
        .create(document.querySelector('#content_editor'), {
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo' ]
        })
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
