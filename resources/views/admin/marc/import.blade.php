@extends('layouts.admin')

@section('title', 'MARCXML Import - SIRITA Admin')
@section('header_title', 'Import Rekam Data MARCXML')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
        max-width: 600px;
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

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }
</style>

<div class="glass-card">
    <h3 style="color: white; margin-top: 0; margin-bottom: 1.5rem; text-align: center;">Unggah Berkas MARCXML (.xml)</h3>
    
    <form action="{{ route('admin.marc.import.post') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="marc_file" class="form-label">Pilih Berkas XML MARC21</label>
            <input type="file" id="marc_file" name="marc_file" class="form-control" accept=".xml" required>
        </div>

        <div style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.2); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; color: #a5b4fc; font-size: 0.9rem; line-height: 1.5;">
            <strong>Catatan Penting:</strong> Berkas yang diunggah harus dalam standar skema **MARCXML** yang valid (menggunakan tag root <code>&lt;collection&gt;</code> atau <code>&lt;record&gt;</code>).
        </div>

        <button type="submit" class="btn-primary">Mulai Proses Impor</button>
    </form>
</div>
@endsection
