@extends('layouts.admin')

@section('title', 'Kelola Menu OPAC - SIRITA Admin')
@section('header_title', 'Kelola Menu Navigasi OPAC')

@section('content')
<style>
    .grid-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    @media (max-width: 992px) {
        .grid-layout {
            grid-template-columns: 1fr;
        }
    }

    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 20px;
        backdrop-filter: blur(12px);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #94a3b8;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
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
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }

    .btn-primary:hover {
        background: var(--primary-hover);
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    th {
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    td {
        color: #f1f5f9;
        font-size: 0.95rem;
    }

    .badge {
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-active {
        background: rgba(16, 185, 129, 0.1);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .badge-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .btn-edit {
        background: transparent;
        border: 1px solid #eab308;
        color: #facc15;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
        text-decoration: none;
        margin-right: 0.5rem;
    }

    .btn-edit:hover {
        background: rgba(234, 179, 8, 0.1);
    }

    .btn-delete {
        background: transparent;
        border: 1px solid #f87171;
        color: #f87171;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s;
    }

    .btn-delete:hover {
        background: rgba(248, 113, 113, 0.1);
    }
</style>

<div class="grid-layout">
    <!-- List of menus -->
    <div class="glass-card">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem; color: #cbd5e1;">Daftar Menu OPAC</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>Nama Menu</th>
                        <th>URL / Route</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td style="font-family: monospace;">{{ $menu->sort_order }}</td>
                            <td style="font-weight: 600;">{{ $menu->name }}</td>
                            <td style="font-family: monospace; color: #38bdf8;">{{ $menu->url }}</td>
                            <td>
                                @if($menu->is_active === 1)
                                    <span class="badge badge-active">Aktif</span>
                                @else
                                    <span class="badge badge-inactive">Non-aktif</span>
                                @endif
                            </td>
                            <td style="text-align: right; display: flex; justify-content: flex-end; align-items: center;">
                                <button onclick="editMenu({{ json_encode($menu) }})" class="btn-edit">Edit</button>
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')" style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Add/Edit Menu -->
    <div class="glass-card">
        <h3 id="form-title" style="margin-top: 0; margin-bottom: 1.5rem; color: #cbd5e1;">Tambah Menu Baru</h3>
        <form id="menu-form" action="{{ route('admin.menus.store') }}" method="POST">
            @csrf
            <input type="hidden" id="method-field" name="_method" value="POST">
            
            <div class="form-group">
                <label for="menu_name" class="form-label">Nama Menu</label>
                <input type="text" id="menu_name" name="name" class="form-control" placeholder="Contoh: Repository" required autocomplete="off">
            </div>

            <div class="form-group">
                <label for="menu_url" class="form-label">URL Tujuan</label>
                <input type="text" id="menu_url" name="url" class="form-control" placeholder="Contoh: / atau /member atau https://url" required autocomplete="off">
                <small style="color: #64748b; font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                    Gunakan <strong>/</strong> untuk Beranda, <strong>/member</strong> untuk Keanggotaan, atau link lengkap (https://..) untuk website luar.
                </small>
            </div>

            <div class="form-group">
                <label for="menu_sort" class="form-label">Nomor Urut</label>
                <input type="number" id="menu_sort" name="sort_order" class="form-control" value="0" required>
            </div>

            <div class="form-group">
                <label for="menu_active" class="form-label">Status Menu</label>
                <select id="menu_active" name="is_active" class="form-control" required style="background: #1e293b;">
                    <option value="1">Aktif</option>
                    <option value="0">Non-aktif</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="submit" id="btn-submit" class="btn-primary">Tambah Menu</button>
                <button type="button" id="btn-cancel" onclick="resetForm()" class="btn-delete" style="display: none; width: 100px;">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function editMenu(menu) {
    document.getElementById('form-title').innerText = 'Edit Menu: ' + menu.name;
    document.getElementById('menu_name').value = menu.name;
    document.getElementById('menu_url').value = menu.url;
    document.getElementById('menu_sort').value = menu.sort_order;
    document.getElementById('menu_active').value = menu.is_active;
    
    // Set edit route & method
    const form = document.getElementById('menu-form');
    form.action = '/admin/menus/' + menu.id;
    document.getElementById('method-field').value = 'PUT';
    
    // Show cancel button
    document.getElementById('btn-submit').innerText = 'Simpan Perubahan';
    document.getElementById('btn-cancel').style.display = 'block';
}

function resetForm() {
    document.getElementById('form-title').innerText = 'Tambah Menu Baru';
    document.getElementById('menu_name').value = '';
    document.getElementById('menu_url').value = '';
    document.getElementById('menu_sort').value = '0';
    document.getElementById('menu_active').value = '1';
    
    // Reset to store route
    const form = document.getElementById('menu-form');
    form.action = "{{ route('admin.menus.store') }}";
    document.getElementById('method-field').value = 'POST';
    
    // Hide cancel button
    document.getElementById('btn-submit').innerText = 'Tambah Menu';
    document.getElementById('btn-cancel').style.display = 'none';
}
</script>
@endsection
