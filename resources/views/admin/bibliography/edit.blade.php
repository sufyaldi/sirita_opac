@extends('layouts.admin')

@section('title', 'Ubah Bibliografi - SIRITA Admin')
@section('header_title', 'Ubah Katalog Bibliografi')

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

        <form action="{{ route('admin.bibliography.update', $bibliography->biblio_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                {{-- Kiri: Detail Utama Buku --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-book-open"></i> Informasi Utama</h3>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Buku / Koleksi *</label>
                        <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $bibliography->title) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="authors" class="form-label">Pengarang / Kontributor</label>
                        <select id="authors" name="authors[]" class="form-control select-multi" multiple style="height: 120px;">
                            @foreach($authors as $author)
                                <option value="{{ $author->author_id }}" {{ in_array($author->author_id, old('authors', $selectedAuthors)) ? 'selected' : '' }}>
                                    {{ $author->author_name }} ({{ $author->authority_type == 'p' ? 'Orang Pribadi' : 'Badan Korporasi' }})
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Tekan Ctrl/Cmd untuk memilih lebih dari satu pengarang.</span>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="isbn_issn" class="form-label">ISBN / ISSN</label>
                            <input type="text" id="isbn_issn" name="isbn_issn" class="form-control" value="{{ old('isbn_issn', $bibliography->isbn_issn) }}">
                        </div>
                        <div class="form-group-half">
                            <label for="publish_year" class="form-label">Tahun Terbit</label>
                            <input type="text" id="publish_year" name="publish_year" class="form-control" value="{{ old('publish_year', $bibliography->publish_year) }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="publisher_id" class="form-label">Penerbit</label>
                            <select id="publisher_id" name="publisher_id" class="form-control">
                                <option value="">-- Pilih Penerbit --</option>
                                @foreach($publishers as $pub)
                                    <option value="{{ $pub->publisher_id }}" {{ old('publisher_id', $bibliography->publisher_id) == $pub->publisher_id ? 'selected' : '' }}>
                                        {{ $pub->publisher_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-half">
                            <label for="publish_place_id" class="form-label">Tempat Terbit</label>
                            <select id="publish_place_id" name="publish_place_id" class="form-control">
                                <option value="">-- Pilih Kota --</option>
                                @foreach($places as $place)
                                    <option value="{{ $place->place_id }}" {{ old('publish_place_id', $bibliography->publish_place_id) == $place->place_id ? 'selected' : '' }}>
                                        {{ $place->place_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edition" class="form-label">Pernyataan Edisi / Cetakan</label>
                        <input type="text" id="edition" name="edition" class="form-control" value="{{ old('edition', $bibliography->edition) }}">
                    </div>
                </div>

                {{-- Kanan: Detail Klasifikasi & Media --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-tags"></i> Klasifikasi & Akses</h3>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="classification" class="form-label">Klasifikasi (DDC)</label>
                            <input type="text" id="classification" name="classification" class="form-control" value="{{ old('classification', $bibliography->classification) }}">
                        </div>
                        <div class="form-group-half">
                            <label for="call_number" class="form-label">Nomor Panggil</label>
                            <input type="text" id="call_number" name="call_number" class="form-control" value="{{ old('call_number', $bibliography->call_number) }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="gmd_id" class="form-label">GMD (Material)</label>
                            <select id="gmd_id" name="gmd_id" class="form-control">
                                @foreach($gmds as $gmd)
                                    <option value="{{ $gmd->gmd_id }}" {{ old('gmd_id', $bibliography->gmd_id) == $gmd->gmd_id ? 'selected' : '' }}>
                                        {{ $gmd->gmd_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-half">
                            <label for="language_id" class="form-label">Bahasa</label>
                            <select id="language_id" name="language_id" class="form-control">
                                @foreach($languages as $lang)
                                    <option value="{{ $lang->language_id }}" {{ old('language_id', $bibliography->language_id) == $lang->language_id ? 'selected' : '' }}>
                                        {{ $lang->language_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="topics" class="form-label">Subjek / Topik Utama</label>
                        <select id="topics" name="topics[]" class="form-control select-multi" multiple style="height: 120px;">
                            @foreach($topics as $topic)
                                <option value="{{ $topic->topic_id }}" {{ in_array($topic->topic_id, old('topics', $selectedTopics)) ? 'selected' : '' }}>
                                    {{ $topic->topic }} ({{ $topic->topic_type == 't' ? 'Topikal' : 'Geografis' }})
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Tekan Ctrl/Cmd untuk memilih lebih dari satu subjek.</span>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="cover_image" class="form-label">Gambar Sampul</label>
                            <input type="file" id="cover_image" name="cover_image" class="form-control" accept="image/*">
                            @if($bibliography->image)
                                <span style="font-size: 0.72rem; color: #34d399; margin-top: 0.25rem; display: block;">Aktif: {{ basename($bibliography->image) }}</span>
                            @endif
                        </div>
                        <div class="form-group-half">
                            <label for="attachment" class="form-label">Berkas E-Book (PDF/EPUB)</label>
                            <input type="file" id="attachment" name="attachment" class="form-control">
                            @if($bibliography->attachments->isNotEmpty())
                                <span style="font-size: 0.72rem; color: #34d399; margin-top: 0.25rem; display: block;">Aktif: {{ $bibliography->attachments->first()->file_title }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Collapsible RDA Metadata (Resource Description Access) --}}
            <div class="rda-accordion" style="margin-top: 1.5rem; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; background: rgba(30, 41, 59, 0.2);">
                <div class="rda-header" onclick="toggleRdaSection()" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none;">
                    <div style="font-weight: 600; color: #cbd5e1; font-size: 0.9rem;"><i class="fas fa-file-invoice text-primary" style="margin-right: 0.5rem;"></i> Metadata RDA (Resource Description Access)</div>
                    <i class="fas fa-chevron-down rda-caret" style="transition: transform 0.25s; color: #64748b; transform: {{ $bibliography->content_type_id || $bibliography->media_type_id || $bibliography->carrier_type_id ? 'rotate(180deg)' : 'rotate(0deg)' }};"></i>
                </div>
                <div class="rda-body" id="rdaBody" style="display: {{ $bibliography->content_type_id || $bibliography->media_type_id || $bibliography->carrier_type_id ? 'grid' : 'none' }}; padding: 1.5rem; border-top: 1px solid var(--border-color); grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Content Type (Tipe Isi)</label>
                        <select name="content_type_id" class="form-control">
                            <option value="">-- Pilih Tipe Isi --</option>
                            <option value="1" {{ old('content_type_id', $bibliography->content_type_id) == 1 ? 'selected' : '' }}>text</option>
                            <option value="2" {{ old('content_type_id', $bibliography->content_type_id) == 2 ? 'selected' : '' }}>spoken word</option>
                            <option value="3" {{ old('content_type_id', $bibliography->content_type_id) == 3 ? 'selected' : '' }}>still image</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Media Type (Tipe Media)</label>
                        <select name="media_type_id" class="form-control">
                            <option value="">-- Pilih Tipe Media --</option>
                            <option value="1" {{ old('media_type_id', $bibliography->media_type_id) == 1 ? 'selected' : '' }}>unmediated</option>
                            <option value="2" {{ old('media_type_id', $bibliography->media_type_id) == 2 ? 'selected' : '' }}>computer</option>
                            <option value="3" {{ old('media_type_id', $bibliography->media_type_id) == 3 ? 'selected' : '' }}>audio</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Carrier Type (Tipe Wadah)</label>
                        <select name="carrier_type_id" class="form-control">
                            <option value="">-- Pilih Tipe Wadah --</option>
                            <option value="1" {{ old('carrier_type_id', $bibliography->carrier_type_id) == 1 ? 'selected' : '' }}>volume</option>
                            <option value="2" {{ old('carrier_type_id', $bibliography->carrier_type_id) == 2 ? 'selected' : '' }}>online resource</option>
                            <option value="3" {{ old('carrier_type_id', $bibliography->carrier_type_id) == 3 ? 'selected' : '' }}>computer disc</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="notes" class="form-label">Sinopsis / Catatan Abstrak</label>
                <textarea id="notes" name="notes" rows="4" class="form-control" placeholder="Tuliskan rangkuman isi buku..." style="resize: vertical;">{{ old('notes', $bibliography->notes) }}</textarea>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui Katalog</button>
                <a href="{{ route('admin.bibliography.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

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
.select-multi { padding: 0.5rem; }

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

<script>
function toggleRdaSection() {
    const rdaBody = document.getElementById('rdaBody');
    const caret = document.querySelector('.rda-caret');
    if (rdaBody.style.display === 'none' || rdaBody.style.display === '') {
        rdaBody.style.display = 'grid';
        caret.style.transform = 'rotate(180deg)';
    } else {
        rdaBody.style.display = 'none';
        caret.style.transform = 'rotate(0deg)';
    }
}
</script>

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


