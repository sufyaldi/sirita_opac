@extends('layouts.admin')

@section('title', 'Tambah ' . $info['name'] . ' - SIRITA Admin')
@section('header_title', 'Tambah ' . $info['name'])

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 3rem;
        border-radius: 20px;
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
    <form action="{{ route('admin.master-file.store', $type) }}" method="POST">
        @csrf
        
        @if(isset($info['manual_pk']))
            <div class="form-group">
                <label for="{{ $info['pk'] }}" class="form-label">{{ $info['pk_label'] }}</label>
                <input type="text" id="{{ $info['pk'] }}" name="{{ $info['pk'] }}" class="form-control" required placeholder="Contoh: ENG">
            </div>
        @endif

        <div class="form-group">
            <label for="{{ $info['field'] }}" class="form-label">{{ $info['label'] }}</label>
            <input type="text" id="{{ $info['field'] }}" name="{{ $info['field'] }}" class="form-control" required placeholder="Masukkan {{ strtolower($info['label']) }}" autofocus>
        </div>

        @if(isset($info['extra_fields']))
            @foreach($info['extra_fields'] as $key => $label)
                <div class="form-group">
                    <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                    <input type="text" id="{{ $key }}" name="{{ $key }}" class="form-control" required placeholder="Masukkan {{ strtolower($label) }}">
                </div>
            @endforeach
        @endif

        <div class="btn-group">
            <button type="submit" class="btn-primary">Simpan Data</button>
            <a href="{{ route('admin.master-file.index', $type) }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
