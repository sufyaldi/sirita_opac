@extends('layouts.admin')

@section('title', 'MARCXML Export - SIRITA Admin')
@section('header_title', 'Ekspor Katalog ke MARCXML')

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
    <form id="export-form" action="{{ route('admin.marc.export.post') }}" method="POST">
        @csrf
        <div class="toolbar">
            <div class="search-form">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari judul/ISBN buku...">
                <button type="submit" formmethod="GET" action="{{ route('admin.marc.export') }}" class="btn-primary">Cari</button>
            </div>
            
            <button type="submit" class="btn-primary">Ekspor ke MARCXML</button>
        </div>

        <div style="overflow-x: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)">
                        </th>
                        <th>Judul Buku</th>
                        <th>Pengarang</th>
                        <th>ISBN / ISSN</th>
                        <th>Penerbit</th>
                        <th>Tahun</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biblios as $biblio)
                        <tr>
                            <td style="text-align: center;">
                                <input type="checkbox" name="biblio_ids[]" value="{{ $biblio->biblio_id }}" class="item-checkbox">
                            </td>
                            <td><strong>{{ $biblio->title }}</strong></td>
                            <td>{{ $biblio->authors->pluck('author_name')->join(', ') ?: '-' }}</td>
                            <td><code>{{ $biblio->isbn_issn ?? '-' }}</code></td>
                            <td>{{ $biblio->publisher->publisher_name ?? '-' }}</td>
                            <td>{{ $biblio->publish_year ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada katalog buku saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    <div class="pagination">
        {{ $biblios->appends(request()->query())->links() }}
    </div>
</div>

<script>
    function toggleSelectAll(master) {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        checkboxes.forEach(cb => cb.checked = master.checked);
    }
</script>
@endsection
