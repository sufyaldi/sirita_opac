@extends('layouts.admin')

@section('title', 'Daftar Eksemplar - SIRITA Admin')
@section('header_title', 'Daftar Eksemplar (Item List)')

@section('content')
<div class="items-wrap">
    <div class="glass-card">
        <div class="header-action-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div class="search-box-wrap" style="width: 380px;">
                <form action="{{ route('admin.items.index') }}" method="GET" style="display: flex; gap: 0.5rem;">
                    <input type="text" name="search" class="form-control" placeholder="Cari Kode Item atau Judul Buku..." value="{{ request('search') }}">
                    <button type="submit" class="btn-primary" style="padding: 0.6rem 1.2rem;"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Kode Item</th>
                        <th>Judul Buku</th>
                        <th>Tipe Koleksi</th>
                        <th>Lokasi Rak</th>
                        <th>No. Panggil</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td><strong style="color: #818cf8;">{{ $item->item_code }}</strong></td>
                            <td style="max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $item->biblio_title ?? 'Tanpa Judul' }}
                            </td>
                            <td>{{ $item->coll_type_name ?? 'Buku Teks' }}</td>
                            <td>{{ $item->location_name ?? 'Rak Utama' }}</td>
                            <td><span style="font-family: monospace;">{{ $item->call_number }}</span></td>
                            <td>
                                @if(strtolower($item->item_status_name ?? '') == 'available' || strtolower($item->item_status_name ?? '') == 'ada')
                                    <span class="badge badge-success">Tersedia</span>
                                @else
                                    <span class="badge badge-warning">{{ $item->item_status_name ?? 'Dipinjam' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons" style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.items.edit', $item->item_id) }}" class="btn-icon btn-edit" title="Edit Eksemplar"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.items.destroy', $item->item_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus eksemplar ini?')" style="margin:0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon btn-delete" title="Hapus"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; color: #64748b; padding: 2rem;">Tidak ada data eksemplar buku ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem;">
            {{ $items->links() }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.items-wrap {
    max-width: 1100px;
    margin: 0 auto;
}
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 2.2rem 2.8rem;
    border-radius: 18px;
    backdrop-filter: blur(12px);
}
.form-control {
    width: 100%; padding: 0.65rem 0.95rem; border-radius: 8px;
    background: rgba(30, 41, 59, 0.4); border: 1px solid var(--border-color);
    color: white; outline: none; box-sizing: border-box; font-size: 0.88rem;
}
.form-control:focus { border-color: var(--primary); }

.btn-primary {
    background: var(--primary); border: none; color: white;
    border-radius: 8px; font-weight: 600; cursor: pointer;
    transition: background 0.3s; display: inline-flex; align-items: center; justify-content: center;
}
.btn-primary:hover { background: var(--primary-hover); }

/* Table design */
.table-responsive { overflow-x: auto; }
.table-modern {
    width: 100%; border-collapse: collapse; text-align: left;
    font-size: 0.88rem; color: #cbd5e1;
}
.table-modern th {
    background: rgba(30, 41, 59, 0.5); padding: 1rem;
    font-weight: 600; color: white; border-bottom: 1px solid var(--border-color);
}
.table-modern td {
    padding: 1rem; border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.table-modern tr:hover td {
    background: rgba(255, 255, 255, 0.01);
}

/* Badges */
.badge {
    padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600;
}
.badge-success { background: rgba(16, 185, 129, 0.15); color: #34d399; }
.badge-warning { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }

/* Buttons action */
.btn-icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--border-color);
    color: #94a3b8; cursor: pointer; transition: all 0.25s; background: transparent;
}
.btn-edit:hover { background: rgba(99, 102, 241, 0.15); color: #818cf8; border-color: var(--primary); }
.btn-delete:hover { background: rgba(239, 68, 68, 0.15); color: #f87171; border-color: #ef4444; }
</style>
@endpush
