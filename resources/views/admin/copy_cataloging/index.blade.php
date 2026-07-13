@extends('layouts.admin')

@section('title', 'Copy Cataloging - SIRITA Admin')
@section('header_title', 'Copy Cataloging (SRU)')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
    }

    .form-inline {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .form-control {
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
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.5rem;
    }

    .admin-table th, .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .admin-table th {
        color: #94a3b8;
        font-weight: 500;
    }

    .admin-table td {
        color: white;
    }
</style>

<div class="glass-card">
    <form action="{{ route('admin.copy-cataloging.index') }}" method="GET" class="form-inline">
        <select name="server" class="form-control" style="min-width: 250px; background-color: #1e293b;">
            <option value="http://z3950.loc.gov:7090/voyager?" {{ $server === 'http://z3950.loc.gov:7090/voyager?' ? 'selected' : '' }}>Library of Congress (USA)</option>
            <option value="http://katalog.perpusnas.go.id/sru" {{ $server === 'http://katalog.perpusnas.go.id/sru' ? 'selected' : '' }}>Perpustakaan Nasional RI</option>
        </select>
        
        <input type="text" name="search" value="{{ $query }}" class="form-control" style="flex: 1; min-width: 250px;" placeholder="Ketik kata kunci judul, pengarang, atau ISBN..." required autofocus>
        
        <button type="submit" class="btn-primary">Cari Katalog</button>
    </form>

    @if(!empty($query))
        <h3 style="margin-top: 2rem; color: white;">Hasil Pencarian untuk "{{ $query }}"</h3>
        
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>ISBN</th>
                        <th>Penerbit / Kota</th>
                        <th>Tahun</th>
                        <th>Klasifikasi</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $index => $book)
                        <tr>
                            <td><strong>{{ $book['title'] }}</strong></td>
                            <td>{{ $book['author'] ?? '-' }}</td>
                            <td><code>{{ $book['isbn'] ?? '-' }}</code></td>
                            <td>
                                {{ $book['publisher'] ?? '-' }}
                                @if(!empty($book['publish_place']))
                                    ({{ $book['publish_place'] }})
                                @endif
                            </td>
                            <td>{{ $book['publish_year'] ?? '-' }}</td>
                            <td><code>{{ $book['classification'] ?? '-' }}</code></td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.copy-cataloging.import') }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <input type="hidden" name="record_id" value="{{ $index }}">
                                    <button type="submit" class="btn-primary" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Impor</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 3rem 0;">Tidak menemukan data katalog pada server luar. Silakan coba server lain atau kata kunci lain.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
