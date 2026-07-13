@extends('layouts.admin')

@section('title', 'Hari Libur - SIRITA Admin')
@section('header_title', 'Pengaturan Hari Libur / Tutup Perpustakaan')

@section('content')
<style>
    .holiday-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .holiday-grid {
            grid-template-columns: 1fr;
        }
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
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
</style>

<div class="holiday-grid">
    <!-- Add Holiday Form -->
    <div class="glass-card">
        <div class="card-title">Tambah Hari Libur / Tutup</div>
        <form action="{{ route('admin.system.holidays.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="holiday_dayname" class="form-label">Nama Hari (Bahasa Inggris)</label>
                <select id="holiday_dayname" name="holiday_dayname" class="form-control" style="background-color: #1e293b;" required>
                    <option value="Sunday">Sunday (Minggu)</option>
                    <option value="Saturday">Saturday (Sabtu)</option>
                    <option value="Monday">Monday (Senin)</option>
                    <option value="Tuesday">Tuesday (Selasa)</option>
                    <option value="Wednesday">Wednesday (Rabu)</option>
                    <option value="Thursday">Thursday (Kamis)</option>
                    <option value="Friday">Friday (Jumat)</option>
                    <option value="Specific Date">Specific Date (Tanggal Khusus)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="holiday_date" class="form-label">Pilih Tanggal (Opsional untuk libur mingguan)</label>
                <input type="date" id="holiday_date" name="holiday_date" class="form-control">
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Keterangan Libur</label>
                <input type="text" id="description" name="description" class="form-control" placeholder="Contoh: Libur Mingguan / Tahun Baru..." required>
            </div>

            <button type="submit" class="btn-primary">Daftarkan Hari Libur</button>
        </form>
    </div>

    <!-- Holidays List Card -->
    <div class="glass-card">
        <div class="card-title">Daftar Hari Libur & Tutup Perpustakaan</div>
        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Hari Terkait</th>
                        <th>Tanggal Libur</th>
                        <th>Keterangan</th>
                        <th style="width: 100px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($holidays as $h)
                        <tr>
                            <td><strong>{{ $h->holiday_dayname }}</strong></td>
                            <td><code>{{ $h->holiday_date ? $h->holiday_date->toDateString() : 'Setiap Minggu' }}</code></td>
                            <td>{{ $h->description }}</td>
                            <td style="text-align: center;">
                                <form action="{{ route('admin.system.holidays.destroy', $h->holiday_id) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus hari libur ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background-color: #ef4444; width: auto;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada hari libur terdaftar. Perpustakaan disetel buka setiap hari.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $holidays->links() }}
        </div>
    </div>
</div>
@endsection
