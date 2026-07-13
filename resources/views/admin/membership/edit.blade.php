@extends('layouts.admin')

@section('title', 'Ubah Anggota - SIRITA Admin')
@section('header_title', 'Ubah Data Anggota')

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
    <form action="{{ route('admin.membership.update', $member->member_id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label class="form-label">ID Anggota / Barcode</label>
            <input type="text" class="form-control" value="{{ $member->member_id }}" disabled style="opacity: 0.6; cursor: not-allowed;">
        </div>

        <div class="form-group">
            <label for="member_name" class="form-label">Nama Lengkap</label>
            <input type="text" id="member_name" name="member_name" value="{{ old('member_name', $member->member_name) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="gender" class="form-label">Jenis Kelamin</label>
            <select id="gender" name="gender" class="form-control" required style="background-color: #1e293b;">
                <option value="1" {{ $member->gender == 1 ? 'selected' : '' }}>Laki-laki</option>
                <option value="0" {{ $member->gender == 0 ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="form-group">
            <label for="member_email" class="form-label">Alamat Email</label>
            <input type="email" id="member_email" name="member_email" value="{{ old('member_email', $member->member_email) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="member_phone" class="form-label">No. Telepon / HP</label>
            <input type="text" id="member_phone" name="member_phone" value="{{ old('member_phone', $member->member_phone) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="member_address" class="form-label">Alamat Rumah</label>
            <input type="text" id="member_address" name="member_address" value="{{ old('member_address', $member->member_address) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="expire_date" class="form-label">Masa Berlaku Hingga</label>
            <input type="date" id="expire_date" name="expire_date" value="{{ old('expire_date', $member->expire_date->toDateString()) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="pin" class="form-label">PIN / Password Baru <span style="font-size: 0.75rem; color: #64748b;">(Kosongkan jika tidak ingin diubah)</span></label>
            <input type="password" id="pin" name="pin" class="form-control" placeholder="Minimal 4 karakter">
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-primary">Perbarui Anggota</button>
            <a href="{{ route('admin.membership.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
