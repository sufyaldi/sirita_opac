@extends('layouts.admin')

@section('title', 'Ubah ' . $info['name'] . ' - SIRITA Admin')
@section('header_title', 'Ubah ' . $info['name'])

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
    <form action="{{ route('admin.master-file.update', ['type' => $type, 'id' => $record->{$info['pk']}]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">ID / Kode</label>
            <input type="text" class="form-control" value="{{ $record->{$info['pk']} }}" disabled style="opacity: 0.6; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label for="{{ $info['field'] }}" class="form-label">{{ $info['label'] }}</label>
            <input type="text" id="{{ $info['field'] }}" name="{{ $info['field'] }}" value="{{ old($info['field'], $record->{$info['field']}) }}" class="form-control" required autofocus>
        </div>

        @if(isset($info['extra_fields']))
            @foreach($info['extra_fields'] as $key => $label)
                <div class="form-group">
                    <label for="{{ $key }}" class="form-label">{{ $label }}</label>
                    <input type="text" id="{{ $key }}" name="{{ $key }}" value="{{ old($key, $record->{$key}) }}" class="form-control" required>
                </div>
            @endforeach
        @endif

        <div class="btn-group">
            <button type="submit" class="btn-primary">Perbarui Data</button>
            <a href="{{ route('admin.master-file.index', $type) }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
