@extends('layouts.admin')

@section('title', 'Ubah Eksemplar - SIRITA Admin')
@section('header_title', 'Ubah Eksemplar Buku')

@section('content')
<div class="form-container-wrap">
    <div class="glass-card">
        @if ($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <ul style="margin: 0; padding-left: 1.2rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.items.update', $item->item_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-grid">
                {{-- Kiri: Detail Buku & Identitas --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-book"></i> Detail Bibliografi</h3>

                    <div class="form-group">
                        <label class="form-label">Judul Buku</label>
                        <input type="text" class="form-control" value="{{ $item->biblio->title ?? 'Tanpa Judul' }}" disabled style="opacity: 0.7;">
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="item_code" class="form-label">Kode Eksemplar (Barcode) *</label>
                            <input type="text" id="item_code" class="form-control" value="{{ $item->item_code }}" disabled style="opacity: 0.7;">
                            <span class="help-text">Kode eksemplar bersifat unik dan tidak dapat diubah.</span>
                        </div>
                        <div class="form-group-half">
                            <label for="inventory_code" class="form-label">Nomor Inventaris</label>
                            <input type="text" id="inventory_code" name="inventory_code" class="form-control" value="{{ old('inventory_code', $item->inventory_code) }}" placeholder="Contoh: INV-...">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="call_number" class="form-label">Nomor Panggil</label>
                            <input type="text" id="call_number" name="call_number" class="form-control" value="{{ old('call_number', $item->call_number) }}" placeholder="Contoh: 005.1 SHA p">
                        </div>
                        <div class="form-group-half">
                            <label for="received_date" class="form-label">Tanggal Diterima</label>
                            <input type="date" id="received_date" name="received_date" class="form-control" value="{{ old('received_date', $item->received_date ? $item->received_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                {{-- Kanan: Parameter Penempatan & Status --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-sliders-h"></i> Penempatan & Status</h3>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="coll_type_id" class="form-label">Tipe Koleksi *</label>
                            <select id="coll_type_id" name="coll_type_id" class="form-control" required>
                                @foreach($collTypes as $type)
                                    <option value="{{ $type->coll_type_id }}" {{ old('coll_type_id', $item->coll_type_id) == $type->coll_type_id ? 'selected' : '' }}>
                                        {{ $type->coll_type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-half">
                            <label for="location_id" class="form-label">Lokasi Rak *</label>
                            <select id="location_id" name="location_id" class="form-control" required>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->location_id }}" {{ old('location_id', $item->location_id) == $loc->location_id ? 'selected' : '' }}>
                                        {{ $loc->location_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="item_status_id" class="form-label">Status Eksemplar *</label>
                            <select id="item_status_id" name="item_status_id" class="form-control" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->item_status_id }}" {{ old('item_status_id', $item->item_status_id) == $status->item_status_id ? 'selected' : '' }}>
                                        {{ $status->item_status_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-half">
                            <label for="source" class="form-label">Asal Pengadaan *</label>
                            <select id="source" name="source" class="form-control" required>
                                <option value="1" {{ old('source', $item->source) == 1 ? 'selected' : '' }}>Beli</option>
                                <option value="2" {{ old('source', $item->source) == 2 ? 'selected' : '' }}>Hadiah / Hibah</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="price" class="form-label">Harga Pembelian (Rupiah)</label>
                        <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $item->price) }}" placeholder="Contoh: 75000">
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui Eksemplar</button>
                <a href="{{ route('admin.items.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-container-wrap {
    max-width: 1000px;
    margin: 0 auto;
}
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 2.2rem 2.8rem;
    border-radius: 18px;
    backdrop-filter: blur(12px);
}
.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.2);
    color: #f87171;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    font-size: 0.88rem;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2.5rem;
}
.column-subtitle {
    font-size: 1rem;
    font-weight: 700;
    color: white;
    margin: 0 0 1.25rem 0;
    border-bottom: 1px dashed rgba(255,255,255,0.06);
    padding-bottom: 0.5rem;
}
.column-subtitle i { margin-right: 0.4rem; color: var(--primary); }

.form-group { margin-bottom: 1.2rem; }
.form-group-row { display: flex; gap: 1rem; margin-bottom: 1.2rem; }
.form-group-half { flex: 1; }

.form-label {
    display: block; margin-bottom: 0.45rem;
    color: #94a3b8; font-weight: 600; font-size: 0.85rem;
}
.form-control {
    width: 100%; padding: 0.7rem 0.95rem; border-radius: 8px;
    background: rgba(30, 41, 59, 0.4); border: 1px solid var(--border-color);
    color: white; outline: none; box-sizing: border-box; font-size: 0.88rem;
    font-family: inherit; transition: border-color 0.25s;
}
.form-control:focus { border-color: var(--primary); }

.help-text { font-size: 0.72rem; color: #64748b; margin-top: 0.25rem; display: block; }

.btn-group { display: flex; gap: 1rem; margin-top: 2.2rem; }
.btn-primary {
    background: var(--primary); border: none; color: white;
    padding: 0.75rem 1.8rem; border-radius: 8px; font-weight: 600;
    font-size: 0.88rem; cursor: pointer; transition: background 0.3s;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-primary:hover { background: var(--primary-hover); }
.btn-secondary {
    background: transparent; border: 1px solid var(--border-color);
    color: #94a3b8; padding: 0.75rem 1.8rem; border-radius: 8px;
    font-weight: 600; font-size: 0.88rem; cursor: pointer;
    text-decoration: none; transition: all 0.3s;
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-secondary:hover { background: rgba(255, 255, 255, 0.05); color: white; }
</style>
@endpush
