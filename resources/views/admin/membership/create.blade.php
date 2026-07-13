@extends('layouts.admin')

@section('title', 'Tambah Anggota - SIRITA Admin')
@section('header_title', 'Tambah Anggota Baru')

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
    <form action="{{ route('admin.membership.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="member_id" class="form-label">ID Anggota / Barcode</label>
            <input type="text" id="member_id" name="member_id" class="form-control" placeholder="Contoh: M00003" required>
        </div>

        <div class="form-group">
            <label for="member_name" class="form-label">Nama Lengkap</label>
            <input type="text" id="member_name" name="member_name" class="form-control" placeholder="Contoh: Rian Pratama" required>
        </div>

        <div class="form-group">
            <label for="gender" class="form-label">Jenis Kelamin</label>
            <select id="gender" name="gender" class="form-control" required style="background-color: #1e293b;">
                <option value="1">Laki-laki</option>
                <option value="0">Perempuan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="member_email" class="form-label">Alamat Email</label>
            <input type="email" id="member_email" name="member_email" class="form-control" placeholder="Contoh: rian@example.com">
        </div>

        <div class="form-group">
            <label for="member_phone" class="form-label">No. Telepon / HP</label>
            <input type="text" id="member_phone" name="member_phone" class="form-control" placeholder="Contoh: 081234567890">
        </div>

        <div class="form-group">
            <label for="member_address" class="form-label">Alamat Rumah</label>
            <input type="text" id="member_address" name="member_address" class="form-control" placeholder="Contoh: Jl. Merdeka No. 45">
        </div>

        <div class="form-group">
            <label for="expire_date" class="form-label">Masa Berlaku Hingga</label>
            <input type="date" id="expire_date" name="expire_date" value="{{ date('Y-m-d', strtotime('+1 year')) }}" class="form-control" required>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-primary">Daftarkan Anggota</button>
            <a href="{{ route('admin.membership.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
