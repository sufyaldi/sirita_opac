@extends('layouts.admin')

@section('title', 'Ubah Bibliografi - SIRITA Admin')
@section('header_title', 'Ubah Katalog Bibliografi')

@section('content')
<style>
    .glass-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 3rem;
        border-radius: 20px;
        backdrop-filter: blur(12px);
        max-width: 800px;
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
        font-family: inherit;
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
    <form action="{{ route('admin.bibliography.update', $bibliography->biblio_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label for="title" class="form-label">Judul Buku / Dokumen</label>
            <input type="text" id="title" name="title" value="{{ old('title', $bibliography->title) }}" class="form-control" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="isbn_issn" class="form-label">ISBN / ISSN</label>
                <input type="text" id="isbn_issn" name="isbn_issn" value="{{ old('isbn_issn', $bibliography->isbn_issn) }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="publish_year" class="form-label">Tahun Terbit</label>
                <input type="text" id="publish_year" name="publish_year" value="{{ old('publish_year', $bibliography->publish_year) }}" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="edition" class="form-label">Edisi / Cetakan</label>
                <input type="text" id="edition" name="edition" value="{{ old('edition', $bibliography->edition) }}" class="form-control">
            </div>
            <div class="form-group">
                <label for="call_number" class="form-label">No. Panggil</label>
                <input type="text" id="call_number" name="call_number" value="{{ old('call_number', $bibliography->call_number) }}" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label for="classification" class="form-label">Klasifikasi</label>
            <input type="text" id="classification" name="classification" value="{{ old('classification', $bibliography->classification) }}" class="form-control">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <div class="form-group">
                <label for="cover_image" class="form-label">Gambar Sampul (Cover Image)</label>
                <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                @if($bibliography->image)
                    <span style="font-size: 0.85rem; color: #34d399;">Sampul aktif: {{ $bibliography->image }}</span>
                @endif
            </div>
            <div class="form-group">
                <label for="attachment" class="form-label">Lampiran Dokumen / E-Book (PDF/EPUB)</label>
                <input type="file" id="attachment" name="attachment" class="form-control">
                @if($bibliography->attachments->isNotEmpty())
                    <span style="font-size: 0.85rem; color: #34d399;">Berkas terlampir: {{ $bibliography->attachments->first()->file_title }}</span>
                @endif
            </div>
        </div>

        <div class="form-group">
            <label for="notes" class="form-label">Catatan / Abstrak Singkat</label>
            <textarea id="notes" name="notes" rows="4" class="form-control">{{ old('notes', $bibliography->notes) }}</textarea>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn-primary">Perbarui Katalog</button>
            <a href="{{ route('admin.bibliography.index') }}" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>

{{-- ================================================================ --}}
{{-- PANEL TAGGING UNIT SCALABLE — Untuk Akreditasi BAN-PT / LAM      --}}
{{-- ================================================================ --}}
<div class="glass-card" style="max-width: 800px; margin: 1.5rem auto; padding: 2rem;">
    <h2 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 0.35rem; color: #f1f5f9;">
        📋 Koleksi Unit Perpustakaan <span style="font-size: 0.75rem; color: #64748b; font-weight: 400;">(untuk Akreditasi BAN-PT / LAM)</span>
    </h2>
    <p style="color: #64748b; font-size: 0.85rem; margin: 0 0 1.5rem;">
        Pilih program studi atau unit kerja yang <strong>mengklaim</strong> judul ini sebagai bagian dari koleksinya untuk akreditasi.
    </p>

    @if(session('tag_success'))
        <div style="background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.5rem; color: #6ee7b7; font-size: 0.87rem;">
            ✓ {{ session('tag_success') }}
        </div>
    @endif

    <style>
    .chips-container { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 1.5rem; }
    .unit-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.8rem;
        border-radius: 99px;
        font-size: 0.85rem;
        background: rgba(30,41,59,0.6);
        border: 1px solid rgba(255,255,255,0.08);
        color: #cbd5e1;
        transition: all 0.2s;
    }
    .unit-chip.chip-core {
        background: rgba(245,158,11,0.08);
        border-color: rgba(245,158,11,0.3);
        color: #f59e0b;
    }
    .unit-chip .btn-remove-chip {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 0;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
    }
    .unit-chip .btn-remove-chip:hover { color: #ef4444; }

    .tagger-box {
        background: rgba(15,23,42,0.4);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 12px;
        padding: 1.25rem;
    }
    .search-select-group {
        display: grid;
        grid-template-columns: 1fr 120px auto;
        gap: 0.75rem;
        align-items: center;
    }
    </style>

    <!-- List unit kerja yang sudah mengklaim buku ini -->
    <div style="margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 600; color: #94a3b8;">Unit Kerja yang Mengklaim Judul Ini ({{ $bibliography->orgUnits->count() }}):</div>
    <div class="chips-container">
        @forelse($bibliography->orgUnits as $ou)
            <div class="unit-chip {{ $ou->pivot->is_core ? 'chip-core' : '' }}">
                <span class="unit-badge ub-{{ $ou->type }}" style="font-size: 0.6rem; padding: 0.1rem 0.3rem;">{{ $ou->typeLabel() }}</span>
                <span><strong>{{ $ou->name }}</strong></span>
                @if($ou->pivot->is_core)
                    <span style="font-size: 0.75rem; color:#f59e0b;" title="Buku Wajib Kurikulum">★ Inti</span>
                @endif

                <form action="{{ route('admin.accreditation.untag', [$bibliography->biblio_id, $ou->id]) }}" method="POST" style="display: inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remove-chip" title="Hapus Klaim">✕</button>
                </form>
            </div>
        @empty
            <div style="color: #475569; font-size: 0.85rem; padding: 0.5rem 0;">Belum ada unit prodi yang mengklaim judul ini.</div>
        @endforelse
    </div>

    <!-- Form cari dan tambahkan unit -->
    <div class="tagger-box">
        <div style="margin-bottom: 0.75rem; font-size: 0.85rem; font-weight: 600; color: #94a3b8; display: flex; justify-content: space-between;">
            <span>Tambah Klaim Unit Baru:</span>
            <span style="font-weight: normal; font-size: 0.75rem; color: #475569;">Cepat cari dengan mengetik nama prodi</span>
        </div>
        
        <form action="{{ route('admin.accreditation.tag', $bibliography->biblio_id) }}" method="POST">
            @csrf
            <div class="search-select-group">
                <div>
                    <!-- Dropdown dengan option grouped per tipe untuk mempermudah pencarian visual -->
                    <select name="unit_id" id="search-unit-select" class="form-control" required style="width: 100%;">
                        <option value="">-- Cari & Pilih Unit Kerja / Prodi --</option>
                        @php
                            $grouped = $allUnits->where('type', '!=', 'university')->groupBy('type');
                        @endphp
                        @foreach($grouped as $type => $typeUnits)
                            <optgroup label="{{ \App\Models\OrgUnit::typeLabels()[$type] ?? $type }}">
                                @foreach($typeUnits->sortBy('name') as $unitItem)
                                    @if(in_array($unitItem->id, $taggedUnitIds)) @continue @endif
                                    <option value="{{ $unitItem->id }}">
                                        {{ $unitItem->name }} ({{ $unitItem->code }}) 
                                        @if($unitItem->parent) · Induk: {{ $unitItem->parent->short_name ?: $unitItem->parent->name }} @endif
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="core-toggle" style="justify-content: center; height: 100%; border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; background: rgba(0,0,0,0.2); margin: 0; padding: 0.4rem 0.5rem;">
                        <input type="checkbox" name="is_core" value="1">
                        <span style="font-size: 0.8rem; color: #cbd5e1; font-weight: 600;">★ Buku Inti</span>
                    </label>
                </div>
                <div>
                    <button type="submit" class="btn-submit" style="padding: 0.65rem 1.25rem;">+ Tambahkan</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Integrasi filter script sederhana agar select bisa dicari jika item > 100 -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Karena kita tidak menggunakan framework pihak ketiga (Select2), 
        // kita tambahkan search box sederhana di atas select jika ingin super user-friendly.
        const select = document.getElementById('search-unit-select');
        
        // Kita bisa menyisipkan kolom input text tepat di atas select untuk filter instan
        const filterInput = document.createElement('input');
        filterInput.type = 'text';
        filterInput.placeholder = '🔍 Ketik nama unit/prodi untuk menyaring pilihan di bawah...';
        filterInput.className = 'form-control';
        filterInput.style.marginBottom = '0.5rem';
        filterInput.style.fontSize = '0.85rem';
        
        select.parentNode.insertBefore(filterInput, select);

        // Cari opsi berdasarkan input user
        filterInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const optgroups = select.getElementsByTagName('optgroup');
            
            for (let og of optgroups) {
                let hasVisibleOption = false;
                const options = og.getElementsByTagName('option');
                
                for (let opt of options) {
                    const text = opt.textContent.toLowerCase();
                    if (text.includes(query)) {
                        opt.style.display = '';
                        hasVisibleOption = true;
                    } else {
                        opt.style.display = 'none';
                    }
                }
                
                // Sembunyikan optgroup jika tidak memiliki option yang cocok
                if (hasVisibleOption) {
                    og.style.display = '';
                } else {
                    og.style.display = 'none';
                }
            }
        });
    });
    </script>
</div>
@endsection


