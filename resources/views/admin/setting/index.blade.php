@extends('layouts.admin')

@section('title', 'Pengaturan - SIRITA Admin')
@section('header_title', 'Pengaturan Sistem')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 3rem;
        border-radius: 20px;
        backdrop-filter: blur(12px);
        max-width: 700px;
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
</style>

<div class="glass-card">
    <form action="{{ route('admin.setting.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="library_name" class="form-label">Nama Perpustakaan</label>
            <input type="text" id="library_name" name="library_name" class="form-control" value="{{ old('library_name', $settings['library_name']) }}" required>
        </div>

        <div class="form-group">
            <label for="fine_rate" class="form-label">Tarif Denda Harian (Rp)</label>
            <input type="number" id="fine_rate" name="fine_rate" class="form-control" value="{{ old('fine_rate', $settings['fine_rate']) }}" required>
        </div>

        <div class="form-group">
            <label for="loan_limit" class="form-label">Batas Jumlah Peminjaman Buku</label>
            <input type="number" id="loan_limit" name="loan_limit" class="form-control" value="{{ old('loan_limit', $settings['loan_limit']) }}" required>
        </div>

        <div class="form-group">
            <label for="loan_period" class="form-label">Durasi Peminjaman Standar (Hari)</label>
            <input type="number" id="loan_period" name="loan_period" class="form-control" value="{{ old('loan_period', $settings['loan_period']) }}" required>
        </div>

        <div class="form-group">
            <label for="opac_theme" class="form-label">Template Tampilan OPAC Publik</label>
            <select id="opac_theme" name="opac_theme" class="form-control" required style="background: #1e293b;">
                <option value="default" {{ $settings['opac_theme'] === 'default' ? 'selected' : '' }}>default (Hijau Tosca Modern)</option>
                <option value="classic" {{ $settings['opac_theme'] === 'classic' ? 'selected' : '' }}>classic (Tema SLiMS Klasik)</option>
                <option value="minimal" {{ $settings['opac_theme'] === 'minimal' ? 'selected' : '' }}>minimal (Tema Putih Bersih)</option>
            </select>
        </div>

        <button type="submit" class="btn-primary">Simpan Pengaturan</button>
    </form>
</div>
@endsection
