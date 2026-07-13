@extends('layouts.admin')

@section('title', 'Ubah Anggota - SIRITA Admin')
@section('header_title', 'Ubah Data Anggota')

@section('content')
<div class="form-container-wrap">
    <div class="glass-card">
        <div class="card-header-simple">
            <h2 class="form-section-title"><i class="fas fa-user-edit text-primary"></i> Ubah Data Anggota</h2>
            <p class="form-section-subtitle">Perbarui informasi anggota perpustakaan <code>{{ $member->member_id }}</code>.</p>
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

        <form action="{{ route('admin.membership.update', $member->member_id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                {{-- Kiri --}}
                <div class="form-column">
                    <div class="form-group">
                        <label class="form-label">ID Anggota / Barcode</label>
                        <input type="text" class="form-control" value="{{ $member->member_id }}" disabled style="opacity: 0.55; cursor: not-allowed;">
                    </div>

                    <div class="form-group">
                        <label for="member_name" class="form-label">Nama Lengkap *</label>
                        <input type="text" id="member_name" name="member_name" value="{{ old('member_name', $member->member_name) }}" class="form-control" required>
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="gender" class="form-label">Jenis Kelamin *</label>
                            <select id="gender" name="gender" class="form-control" required>
                                <option value="1" {{ old('gender', $member->gender) == 1 ? 'selected' : '' }}>Laki-laki</option>
                                <option value="0" {{ old('gender', $member->gender) == 0 ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group-half">
                            <label for="member_type_id" class="form-label">Tipe Anggota *</label>
                            <select id="member_type_id" name="member_type_id" class="form-control" required>
                                @foreach($memberTypes as $type)
                                    <option value="{{ $type->member_type_id }}" {{ old('member_type_id', $member->member_type_id) == $type->member_type_id ? 'selected' : '' }}>
                                        {{ $type->member_type_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pin" class="form-label">PIN / Password Baru <span style="font-size: 0.72rem; color: #64748b;">(Kosongkan jika tetap)</span></label>
                        <input type="password" id="pin" name="pin" class="form-control" placeholder="Ubah password/PIN">
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="form-column">
                    <div class="form-group">
                        <label for="member_email" class="form-label">Alamat Email</label>
                        <input type="email" id="member_email" name="member_email" value="{{ old('member_email', $member->member_email) }}" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="member_phone" class="form-label">No. Telepon / HP</label>
                        <input type="text" id="member_phone" name="member_phone" value="{{ old('member_phone', $member->member_phone) }}" class="form-control">
                    </div>

                    <div class="form-group-row">
                        <div class="form-group-half">
                            <label for="inst_name" class="form-label">Nama Institusi</label>
                            <input type="text" id="inst_name" name="inst_name" value="{{ old('inst_name', $member->inst_name) }}" class="form-control" placeholder="Contoh: IAIN Parepare">
                        </div>
                        <div class="form-group-half">
                            <label for="expire_date" class="form-label">Masa Berlaku *</label>
                            <input type="date" id="expire_date" name="expire_date" value="{{ old('expire_date', $member->expire_date ? $member->expire_date->toDateString() : '') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="member_address" class="form-label">Alamat Rumah</label>
                        <textarea id="member_address" name="member_address" class="form-control form-textarea" placeholder="Masukkan alamat lengkap">{{ old('member_address', $member->member_address) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Perbarui Anggota</button>
                <a href="{{ route('admin.membership.index') }}" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Centering & container spacing */
.form-container-wrap {
    max-width: 960px;
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
.form-section-subtitle {
    color: #64748b;
    font-size: .875rem;
    margin: 0;
}

/* Grid Layout */
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.form-group {
    margin-bottom: 1.25rem;
}
.form-group-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.25rem;
}
.form-group-half {
    flex: 1;
}

.form-label {
    display: block;
    margin-bottom: 0.45rem;
    color: #94a3b8;
    font-weight: 600;
    font-size: .82rem;
}

.form-control {
    width: 100%;
    padding: .65rem .95rem;
    border-radius: 8px;
    background: rgba(30, 41, 59, 0.45);
    border: 1px solid var(--border-color);
    color: white;
    outline: none;
    box-sizing: border-box;
    font-size: .875rem;
    transition: border-color .2s;
}
.form-control:focus {
    border-color: var(--primary);
}
select.form-control option {
    background-color: #0f172a;
    color: white;
}
.form-textarea {
    resize: none;
    height: 78px;
}

/* Buttons */
.btn-group {
    display: flex;
    gap: 1rem;
    margin-top: 1.8rem;
    border-top: 1px solid var(--border-color);
    padding-top: 1.5rem;
}
.btn-primary {
    background: var(--primary);
    border: none;
    color: white;
    padding: .7rem 1.6rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: .875rem;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    transition: background .25s;
}
.btn-primary:hover {
    background: var(--primary-hover);
}
.btn-secondary {
    background: transparent;
    border: 1px solid var(--border-color);
    color: #94a3b8;
    padding: .7rem 1.6rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: .875rem;
    cursor: pointer;
    text-decoration: none;
    transition: all .25s;
}
.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.05);
    color: white;
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .9rem 1.2rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    font-size: .875rem;
}
.alert-error {
    background: rgba(239, 68, 68, 0.1);
    color: #f87171;
    border: 1px solid rgba(239, 68, 68, 0.25);
}
.text-primary {
    color: var(--primary);
}
</style>
@endpush
