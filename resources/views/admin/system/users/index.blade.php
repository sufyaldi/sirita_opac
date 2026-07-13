@extends('layouts.admin')

@section('title', 'Pustakawan & Hak Akses - SIRITA Admin')
@section('header_title', 'Manajemen Staf & Pengguna Sistem')

@section('content')
<style>
    .users-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .users-grid {
            grid-template-columns: 1fr;
        }
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
        box-sizing: border-box;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: white;
    }

    .form-group {
        margin-bottom: 1.25rem;
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
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
        text-align: center;
        text-decoration: none;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
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

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: white;
        cursor: pointer;
    }
</style>

<div class="users-grid">
    <!-- Librarians list & creation form -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Add user Form -->
        <div class="glass-card">
            <div class="card-title">Tambah Pustakawan / Staf</div>
            <form action="{{ route('admin.system.users.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Contoh: siska123" required>
                </div>
                <div class="form-group">
                    <label for="realname" class="form-label">Nama Lengkap</label>
                    <input type="text" id="realname" name="realname" class="form-control" placeholder="Contoh: Siska Lestari" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="siska@sirita.local">
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="******" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Kelompok / Hak Akses</label>
                    <div class="checkbox-group">
                        @foreach($groups as $g)
                            <label class="checkbox-label">
                                <input type="checkbox" name="groups[]" value="{{ $g->group_id }}">
                                {{ $g->group_name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-primary">Buat Akun</button>
            </form>
        </div>

        <!-- Librarians list -->
        <div class="glass-card">
            <div class="card-title">Daftar Pustakawan Aktif</div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Username / Nama</th>
                            <th>Email</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->username }}</strong><br>
                                    <span style="font-size: 0.85rem; color: #94a3b8;">{{ $user->realname }}</span>
                                </td>
                                <td>{{ $user->email ?? '-' }}</td>
                                <td style="text-align: center;">
                                    @if($user->username !== 'admin')
                                        <form action="{{ route('admin.system.users.destroy', $user->user_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pustakawan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #ef4444; width: auto;">Hapus</button>
                                        </form>
                                    @else
                                        <span style="color: #64748b; font-size: 0.85rem;">System</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 1rem;">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- User Groups administration -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Add Group Form -->
        <div class="glass-card">
            <div class="card-title">Tambah Kelompok Pengguna</div>
            <form action="{{ route('admin.system.users.groups.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="group_name" class="form-label">Nama Kelompok / Hak Akses</label>
                    <input type="text" id="group_name" name="group_name" class="form-control" placeholder="Contoh: Pustakawan Utama, Staf Magang" required>
                </div>
                <button type="submit" class="btn-primary">Buat Kelompok</button>
            </form>
        </div>

        <!-- Groups list -->
        <div class="glass-card">
            <div class="card-title">Daftar Kelompok Pengguna</div>
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama Kelompok</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $g)
                            <tr>
                                <td><strong>{{ $g->group_name }}</strong></td>
                                <td style="text-align: center;">
                                    <form action="{{ route('admin.system.users.groups.destroy', $g->group_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelompok ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #ef4444; width: auto;">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada kelompok pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
