@extends('opac.templates.default.layout')

@section('title', 'Area Keanggotaan - SIRITA OPAC')

@section('content')
<style>
    .member-hero {
        text-align: center;
        margin-bottom: 3rem;
    }

    .member-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(to right, #818cf8, #c084fc);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0.5rem;
    }

    .member-hero p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .login-card {
        max-width: 440px;
        margin: 0 auto;
        background: rgba(30, 41, 59, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 2.5rem;
        backdrop-filter: blur(16px);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
    }

    .login-card h2 {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #f8fafc;
    }

    .login-card .subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: white;
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        box-sizing: border-box;
        transition: border-color 0.3s, box-shadow 0.3s;
        outline: none;
    }

    .form-group input:focus {
        border-color: rgba(99, 102, 241, 0.6);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .form-group input::placeholder {
        color: #475569;
    }

    .error-msg {
        color: #f87171;
        font-size: 0.8rem;
        margin-top: 0.4rem;
        display: block;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.15);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #6ee7b7;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }

    .btn-login {
        width: 100%;
        padding: 0.9rem;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 10px;
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
        margin-top: 0.5rem;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    }

    .btn-login:hover {
        background: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .divider {
        text-align: center;
        color: #475569;
        font-size: 0.8rem;
        margin: 1.5rem 0;
        position: relative;
    }

    .divider::before, .divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 38%;
        height: 1px;
        background: rgba(255,255,255,0.1);
    }

    .divider::before { left: 0; }
    .divider::after { right: 0; }

    .info-box {
        background: rgba(99, 102, 241, 0.08);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1.5rem;
    }

    .info-box p {
        color: #a5b4fc;
        font-size: 0.82rem;
        margin: 0;
        line-height: 1.6;
    }

    .icon-user {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(192, 132, 252, 0.2));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.25rem;
    }
</style>

<div class="member-hero">
    <h1>Area Keanggotaan</h1>
    <p>Login untuk melihat riwayat peminjaman dan informasi keanggotaan Anda</p>
</div>

<div class="login-card">
    <div class="icon-user">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
        </svg>
    </div>

    <h2>Masuk ke Akun Anggota</h2>
    <p class="subtitle">Gunakan Nomor Anggota dan PIN yang diberikan oleh pustakawan</p>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    @if($errors->has('member_id'))
        <div class="alert-error">⚠ {{ $errors->first('member_id') }}</div>
    @endif

    <form action="{{ route('opac.member.login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="member_id">Nomor Anggota</label>
            <input
                type="text"
                id="member_id"
                name="member_id"
                value="{{ old('member_id') }}"
                placeholder="Contoh: MBR-001"
                autocomplete="username"
                autofocus
            >
            @error('member_id')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="pin">PIN / Password</label>
            <input
                type="password"
                id="pin"
                name="pin"
                placeholder="Masukkan PIN 4 digit atau password"
                autocomplete="current-password"
            >
            @error('pin')
                <span class="error-msg">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-login">
            Masuk ke Area Keanggotaan
        </button>
    </form>

    <div class="divider">atau</div>

    <div class="info-box">
        <p>
            <strong style="color: #c4b5fd;">Belum memiliki akun?</strong><br>
            Silakan kunjungi meja sirkulasi perpustakaan untuk mendaftar sebagai anggota baru.
            Petugas akan membuatkan nomor anggota dan PIN untuk Anda.
        </p>
    </div>
</div>
@endsection
