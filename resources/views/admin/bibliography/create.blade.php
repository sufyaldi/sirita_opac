@extends('layouts.admin')

@section('title', 'Tambah Bibliografi - SIRITA Admin')
@section('header_title', 'Tambah Bibliografi Baru')

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

        <form action="{{ route('admin.bibliography.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid">
                {{-- Kiri: Detail Utama Buku --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-book-open"></i> Informasi Utama</h3>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">Judul Buku / Koleksi *</label>
                        <input type="text" id="title" name="title" class="form-control" placeholder="Masukkan judul utama buku" value="{{ old('title') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="authors" class="form-label">Pengarang / Kontributor</label>
                        <select id="authors" name="authors[]" class="form-control select-multi" multiple style="height: 120px;">
                            @foreach($authors as $author)
                                <option value="{{ $author->author_id }}" {{ is_array(old('authors')) && in_array($author->author_id, old('authors')) ? 'selected' : '' }}>
                                    {{ $author->author_name }} ({{ $author->authority_type == 'p' ? 'Orang Pribadi' : 'Badan Korporasi' }})
                                </option>
                            @endforeach
                        </select>
                        <span class="help-text">Tekan Ctrl/Cmd untuk memilih lebih dari satu pengarang.</span>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="isbn_issn" class="form-label">ISBN / ISSN</label>
                            <input type="text" id="isbn_issn" name="isbn_issn" class="form-control" placeholder="978-..." value="{{ old('isbn_issn') }}">
                        </div>
                        <div class="form-group-half">
                            <label for="publish_year" class="form-label">Tahun Terbit</label>
                            <input type="text" id="publish_year" name="publish_year" class="form-control" placeholder="Contoh: 2026" value="{{ old('publish_year') }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="publisher_id" class="form-label">Penerbit</label>
                            <select id="publisher_id" name="publisher_id" class="form-control">
                                <option value="">-- Pilih Penerbit --</option>
                                @foreach($publishers as $pub)
                                    <option value="{{ $pub->publisher_id }}" {{ old('publisher_id') == $pub->publisher_id ? 'selected' : '' }}>
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
                                    <option value="{{ $place->place_id }}" {{ old('publish_place_id') == $place->place_id ? 'selected' : '' }}>
                                        {{ $place->place_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edition" class="form-label">Pernyataan Edisi / Cetakan</label>
                        <input type="text" id="edition" name="edition" class="form-control" placeholder="Contoh: Edisi 2" value="{{ old('edition') }}">
                    </div>
                </div>

                {{-- Kanan: Detail Klasifikasi & Media --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-tags"></i> Klasifikasi & Akses</h3>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="classification" class="form-label">Klasifikasi (DDC)</label>
                            <input type="text" id="classification" name="classification" class="form-control" placeholder="Contoh: 005.1" value="{{ old('classification') }}">
                        </div>
                        <div class="form-group-half">
                            <label for="call_number" class="form-label">Nomor Panggil</label>
                            <input type="text" id="call_number" name="call_number" class="form-control" placeholder="Contoh: 005.1 SHA p" value="{{ old('call_number') }}">
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="gmd_id" class="form-label">GMD (Material)</label>
                            <select id="gmd_id" name="gmd_id" class="form-control">
                                @foreach($gmds as $gmd)
                                    <option value="{{ $gmd->gmd_id }}" {{ old('gmd_id', 1) == $gmd->gmd_id ? 'selected' : '' }}>
                                        {{ $gmd->gmd_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-half">
                            <label for="language_id" class="form-label">Bahasa</label>
                            <select id="language_id" name="language_id" class="form-control">
                                @foreach($languages as $lang)
                                    <option value="{{ $lang->language_id }}" {{ old('language_id', 1) == $lang->language_id ? 'selected' : '' }}>
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
                                <option value="{{ $topic->topic_id }}" {{ is_array(old('topics')) && in_array($topic->topic_id, old('topics')) ? 'selected' : '' }}>
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
                        </div>
                        <div class="form-group-half">
                            <label for="attachment" class="form-label">Berkas E-Book (PDF/EPUB)</label>
                            <input type="file" id="attachment" name="attachment" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Collapsible RDA Metadata (Resource Description Access) --}}
            <div class="rda-accordion" style="margin-top: 1.5rem; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; background: rgba(30, 41, 59, 0.2);">
                <div class="rda-header" onclick="toggleRdaSection()" style="padding: 1rem 1.5rem; display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none;">
                    <div style="font-weight: 600; color: #cbd5e1; font-size: 0.9rem;"><i class="fas fa-file-invoice text-primary" style="margin-right: 0.5rem;"></i> Metadata RDA (Resource Description Access)</div>
                    <i class="fas fa-chevron-down rda-caret" style="transition: transform 0.25s; color: #64748b;"></i>
                </div>
                <div class="rda-body" id="rdaBody" style="display: none; padding: 1.5rem; border-top: 1px solid var(--border-color); grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label">Content Type (Tipe Isi)</label>
                        <select name="content_type_id" class="form-control">
                            <option value="">-- Pilih Tipe Isi --</option>
                            <option value="1">text</option>
                            <option value="2">spoken word</option>
                            <option value="3">still image</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Media Type (Tipe Media)</label>
                        <select name="media_type_id" class="form-control">
                            <option value="">-- Pilih Tipe Media --</option>
                            <option value="1">unmediated</option>
                            <option value="2">computer</option>
                            <option value="3">audio</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Carrier Type (Tipe Wadah)</label>
                        <select name="carrier_type_id" class="form-control">
                            <option value="">-- Pilih Tipe Wadah --</option>
                            <option value="1">volume</option>
                            <option value="2">online resource</option>
                            <option value="3">computer disc</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-top: 1.5rem;">
                <label for="notes" class="form-label">Sinopsis / Catatan Abstrak</label>
                <textarea id="notes" name="notes" rows="4" class="form-control" placeholder="Tuliskan rangkuman isi buku..." style="resize: vertical;">{{ old('notes') }}</textarea>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Simpan Katalog</button>
                <a href="{{ route('admin.bibliography.index') }}" class="btn-secondary">Kembali</a>
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
@endpush

@push('scripts')
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
@endpush
