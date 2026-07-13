@extends('layouts.admin')

@section('title', 'Cetak Barcode & Label - SIRITA Admin')
@section('header_title', 'Cetak Barcode & Label Eksemplar')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(12px);
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-form {
        display: flex;
        gap: 0.5rem;
    }

    .form-control {
        padding: 0.6rem 1rem;
        border-radius: 8px;
        background: rgba(30, 41, 59, 0.4);
        border: 1px solid var(--border-color);
        color: white;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
    }

    .btn-primary {
        background: var(--primary);
        border: none;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
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
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.05);
        color: white;
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

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .pagination a, .pagination span {
        padding: 0.4rem 0.8rem;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: white;
        text-decoration: none;
    }

    .pagination .active {
        background: var(--primary);
        border-color: var(--primary);
    }
</style>

<div class="glass-card">
    <form id="print-form" action="" method="POST">
        @csrf
        <div class="toolbar">
            <div class="search-form">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari kode/judul buku...">
                <button type="submit" formmethod="GET" action="{{ route('admin.barcode.index') }}" class="btn-primary">Cari</button>
            </div>
            
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" onclick="submitToPrint('{{ route('admin.barcode.print') }}')" class="btn-primary">Cetak Barcode</button>
                <button type="button" onclick="submitToPrint('{{ route('admin.barcode.label') }}')" class="btn-secondary">Cetak Label Punggung</button>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Kode Eksemplar</th>
                        <th>Judul Buku</th>
                        <th>No. Panggil</th>
                        <th>Lokasi Rak</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="item_codes[]" value="{{ $item->item_code }}" class="item-checkbox">
                            </td>
                            <td><code>{{ $item->item_code }}</code></td>
                            <td><strong>{{ $item->biblio->title ?? '-' }}</strong></td>
                            <td>{{ $item->call_number ?? '-' }}</td>
                            <td>{{ $item->location_id ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada data eksemplar buku saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="pagination">
        {{ $items->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
    }

    function submitToPrint(routeUrl) {
        const checked = document.querySelectorAll('.item-checkbox:checked');
        if (checked.length === 0) {
            alert('Silakan pilih minimal satu eksemplar buku terlebih dahulu.');
            return;
        }
        
        const form = document.getElementById('print-form');
        form.action = routeUrl;
        form.target = '_blank'; // Open print layout in a new tab
        form.submit();
    }
</script>
@endsection
