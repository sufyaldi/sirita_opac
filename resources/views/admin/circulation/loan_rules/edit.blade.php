@extends('layouts.admin')

@section('title', 'Ubah Aturan Peminjaman - SIRITA Admin')
@section('header_title', 'Ubah Aturan')

@section('content')
<div class="form-container-wrap">
    <div class="glass-card">
        <div class="card-header-simple">
            <h2 class="form-section-title"><i class="fas fa-gavel text-primary"></i> Ubah Aturan Peminjaman</h2>
            <p class="form-section-subtitle">Perbarui kombinasi target pengelompokan dan parameter sirkulasi aturan.</p>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <ul style="margin: 0; padding-left: 1.2rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.circulation.loan-rules.update', $rule->loan_rules_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                {{-- Kiri: Target Kategori --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-filter"></i> Target Pengelompokan</h3>
                    
                    <div class="form-group">
                        <label for="member_type_id" class="form-label">Tipe Anggota *</label>
                        <select id="member_type_id" name="member_type_id" class="form-control" required>
                            @foreach($memberTypes as $type)
                                <option value="{{ $type->member_type_id }}" {{ old('member_type_id', $rule->member_type_id) == $type->member_type_id ? 'selected' : '' }}>
                                    {{ $type->member_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="coll_type_id" class="form-label">Tipe Koleksi *</label>
                        <select id="coll_type_id" name="coll_type_id" class="form-control" required>
                            @foreach($collTypes as $type)
                                <option value="{{ $type->coll_type_id }}" {{ old('coll_type_id', $rule->coll_type_id) == $type->coll_type_id ? 'selected' : '' }}>
                                    {{ $type->coll_type_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gmd_id" class="form-label">GMD (General Material Designation) *</label>
                        <select id="gmd_id" name="gmd_id" class="form-control" required>
                            <option value="0" {{ old('gmd_id', $rule->gmd_id) == 0 ? 'selected' : '' }}>Semua GMD (ALL)</option>
                            @foreach($gmds as $gmd)
                                <option value="{{ $gmd->gmd_id }}" {{ old('gmd_id', $rule->gmd_id) == $gmd->gmd_id ? 'selected' : '' }}>
                                    {{ $gmd->gmd_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Kanan: Aturan Parameter --}}
                <div class="form-column">
                    <h3 class="column-subtitle"><i class="fas fa-sliders-h"></i> Parameter Aturan</h3>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="loan_limit" class="form-label">Batas Pinjam (Buku) *</label>
                            <input type="number" id="loan_limit" name="loan_limit" value="{{ old('loan_limit', $rule->loan_limit) }}" class="form-control" min="0" required>
                        </div>
                        <div class="form-group-half">
                            <label for="loan_periode" class="form-label">Durasi Pinjam (Hari) *</label>
                            <input type="number" id="loan_periode" name="loan_periode" value="{{ old('loan_periode', $rule->loan_periode) }}" class="form-control" min="0" required>
                        </div>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="reborrow_limit" class="form-label">Limit Perpanjang *</label>
                            <input type="number" id="reborrow_limit" name="reborrow_limit" value="{{ old('reborrow_limit', $rule->reborrow_limit) }}" class="form-control" min="0" required>
                        </div>
                        <div class="form-group-half">
                            <label for="grace_periode" class="form-label">Masa Tenggang (Grace) *</label>
                            <input type="number" id="grace_periode" name="grace_periode" value="{{ old('grace_periode', $rule->grace_periode) }}" class="form-control" min="0" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fine_each_day" class="form-label">Denda Harian (Rupiah/Hari) *</label>
                        <input type="number" id="fine_each_day" name="fine_each_day" value="{{ old('fine_each_day', $rule->fine_each_day) }}" class="form-control" min="0" required>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui Aturan</button>
                <a href="{{ route('admin.circulation.loan-rules.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Container Centering */
.form-container-wrap {
    max-width: 900px;
    margin: 0 auto;
    padding: 1rem 0;
}
.glass-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    padding: 2.2rem 2.8rem;
    border-radius: 18px;
    backdrop-filter: blur(12px);
}
.card-header-simple {
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 1.2rem;
}
.form-section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: white;
    margin: 0 0 .3rem;
}
.form-section-title i { margin-right: .4rem; }
.form-section-subtitle { color: #64748b; font-size: .875rem; margin: 0; }

/* Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2.5rem;
}
.column-subtitle {
    font-size: .95rem;
    font-weight: 600;
    color: white;
    margin: 0 0 1.25rem;
    border-bottom: 1px dashed rgba(255,255,255,.05);
    padding-bottom: .5rem;
}
.column-subtitle i { margin-right: .3rem; color: var(--primary); }

.form-group { margin-bottom: 1.25rem; }
.form-group-row { display: flex; gap: 1rem; margin-bottom: 1.25rem; }
.form-group-half { flex: 1; }

.form-label {
    display: block; margin-bottom: 0.45rem;
    color: #94a3b8; font-weight: 600; font-size: .82rem;
}
.form-control {
    width: 100%; padding: .65rem .95rem; border-radius: 8px;
    background: rgba(30, 41, 59, 0.45); border: 1px solid var(--border-color);
    color: white; outline: none; box-sizing: border-box; font-size: .875rem;
}
.form-control:focus { border-color: var(--primary); }
select.form-control option { background-color: #0f172a; color: white; }

/* Buttons */
.btn-group {
    display: flex; gap: 1rem; margin-top: 1.8rem;
    border-top: 1px solid var(--border-color); padding-top: 1.5rem;
}
.btn-primary {
    background: var(--primary); border: none; color: white;
    padding: .7rem 1.6rem; border-radius: 8px; font-weight: 600;
    font-size: .875rem; cursor: pointer;
    display: inline-flex; align-items: center; gap: .4rem;
}
.btn-primary:hover { background: var(--primary-hover); }
.btn-secondary {
    background: transparent; border: 1px solid var(--border-color);
    color: #94a3b8; padding: .7rem 1.6rem; border-radius: 8px;
    font-weight: 600; font-size: .875rem; cursor: pointer;
    text-decoration: none; transition: all .25s;
}
.btn-secondary:hover { background: rgba(255, 255, 255, 0.05); color: white; }

/* Alerts */
.alert {
    display: flex; align-items: center; gap: .6rem;
    padding: .9rem 1.2rem; border-radius: 10px;
    margin-bottom: 1.5rem; font-size: .875rem;
}
.alert-error { background: rgba(239, 68, 68, 0.1); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.25); }
.text-primary { color: var(--primary); }
</style>
@endpush
