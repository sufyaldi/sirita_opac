@extends('layouts.admin')
@section('title', 'Kelola Unit Perpustakaan / Prodi')

@section('content')
<style>
.ou-header { margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; }
.ou-header h1 { font-size: 1.6rem; font-weight: 700; margin: 0 0 0.3rem; }
.ou-header p  { color: #94a3b8; margin: 0; font-size: 0.9rem; }

.ou-grid { display: grid; grid-template-columns: 1.6fr 1fr; gap: 2rem; align-items: start; }

.tree-card, .form-card {
    background: rgba(30,41,59,0.7);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    padding: 1.5rem;
    backdrop-filter: blur(10px);
}

.tree-title { font-size: 1.1rem; font-weight: 700; margin-top: 0; margin-bottom: 1.5rem; color: #f1f5f9; display: flex; align-items: center; gap: 0.5rem; }

.tree-list { display: flex; flex-direction: column; gap: 0.75rem; }
.tree-node {
    background: rgba(15,23,42,0.4);
    border: 1px solid rgba(255,255,255,0.04);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}
.tree-node:hover { border-color: rgba(99,102,241,0.3); background: rgba(15,23,42,0.6); }

.node-indent-1 { margin-left: 1.5rem; }
.node-indent-2 { margin-left: 3rem; }

.node-info { display: flex; align-items: center; gap: 0.75rem; }
.node-actions { display: flex; gap: 0.5rem; }

.btn-icon {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.08);
    color: #cbd5e1;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}
.btn-icon:hover { background: rgba(99,102,241,0.2); border-color: #818cf8; color: white; }
.btn-delete:hover { background: rgba(239,68,68,0.2); border-color: #ef4444; color: #f87171; }

.unit-badge {
    font-size: 0.68rem;
    padding: 0.15rem 0.45rem;
    border-radius: 5px;
    font-weight: 700;
    text-transform: uppercase;
}
.ub-faculty { background: rgba(16,185,129,0.15); color: #6ee7b7; }
.ub-department { background: rgba(251,191,36,0.12); color: #fbbf24; }
.ub-main_library { background: rgba(99,102,241,0.15); color: #a5b4fc; }
.ub-lp, .ub-upt, .ub-pusat { background: rgba(100,116,139,0.15); color: #94a3b8; }

.form-group { margin-bottom: 1.25rem; }
.form-label { display: block; margin-bottom: 0.4rem; color: #94a3b8; font-size: 0.85rem; font-weight: 500; }
.form-control {
    width: 100%;
    padding: 0.65rem 0.85rem;
    border-radius: 8px;
    background: rgba(15,23,42,0.5);
    border: 1px solid rgba(255,255,255,0.1);
    color: white;
    box-sizing: border-box;
    font-family: inherit;
    font-size: 0.9rem;
}
.form-control:focus { border-color: #6366f1; outline: none; }

.btn-submit {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.9rem;
}
.btn-submit:hover { opacity: 0.9; }

.btn-cancel {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: #cbd5e1;
    padding: 0.7rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.9rem;
}
.btn-cancel:hover { background: rgba(255,255,255,0.1); color: white; }

.alert-message {
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    font-size: 0.88rem;
}
.alert-success { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); color: #6ee7b7; }
.alert-danger { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; }
</style>

<div class="ou-header">
    <div>
        <h1>🏛️ Kelola Unit Perpustakaan / Prodi</h1>
        <p>Atur hierarki organisasi perpustakaan, fakultas, program studi, lembaga, dan pusat studi</p>
    </div>
</div>

@if(session('success'))
    <div class="alert-message alert-success">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert-message alert-danger">
        ✗ {{ session('error') }}
    </div>
@endif

<div class="ou-grid">
    <div class="tree-card">
        <h2 class="tree-title">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            Hierarki Unit saat ini
        </h2>

        <div class="tree-list">
            @if(isset($univNode))
                <div class="tree-node" style="background: rgba(99,102,241,0.05); border-color: rgba(99,102,241,0.2);">
                    <div class="node-info">
                        <span class="unit-badge" style="background: rgba(168,85,247,0.15); color: #d8b4fe;">{{ $univNode->typeLabel() }}</span>
                        <strong style="color: #c084fc;">{{ $univNode->name }}</strong>
                    </div>
                    <div class="node-actions">
                        <button type="button" class="btn-icon" onclick="editUnit({{ json_encode($univNode) }})" title="Edit">✏️</button>
                    </div>
                </div>
            @endif

            @forelse($units as $root)
                <div class="tree-node node-indent-1">
                    <div class="node-info">
                        <span class="unit-badge ub-{{ $root->type }}">{{ $root->typeLabel() }}</span>
                        <span>{{ $root->name }} <code style="color: #475569; font-size: 0.8rem;">({{ $root->code }})</code></span>
                    </div>
                    <div class="node-actions">
                        <button type="button" class="btn-icon" onclick="editUnit({{ json_encode($root) }})">✏️</button>
                        <form action="{{ route('admin.org-units.destroy', $root->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus unit ini?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon btn-delete">🗑️</button>
                        </form>
                    </div>
                </div>

                @foreach($root->children->sortBy('sort_order') as $child)
                    <div class="tree-node node-indent-2">
                        <div class="node-info">
                            <span style="color: #334155;">└</span>
                            <span class="unit-badge ub-{{ $child->type }}">{{ $child->typeLabel() }}</span>
                            <span>{{ $child->name }} <code style="color: #475569; font-size: 0.8rem;">({{ $child->code }})</code></span>
                            @if($child->student_count)
                                <span style="font-size: 0.8rem; color: #475569;">· {{ number_format($child->student_count) }} mhs</span>
                            @endif
                        </div>
                        <div class="node-actions">
                            <button type="button" class="btn-icon" onclick="editUnit({{ json_encode($child) }})">✏️</button>
                            <form action="{{ route('admin.org-units.destroy', $child->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus unit ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete">🗑️</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            @empty
                <div style="color: #475569; text-align: center; padding: 2rem;">
                    Belum ada unit kerja. Tambahkan di form sebelah kanan.
                </div>
            @endforelse
        </div>
    </div>

    <div class="form-card">
        <h2 class="tree-title" id="form-title">➕ Tambah Unit Kerja Baru</h2>

        <form id="unit-form" action="{{ route('admin.org-units.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">

            <div class="form-group">
                <label class="form-label" for="name">Nama Unit Kerja / Program Studi *</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: Prodi Teknik Kimia" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="code">Kode Unit * <span style="font-size: 0.75rem; color: #64748b;">(Harus Unik)</span></label>
                <input type="text" name="code" id="code" class="form-control" placeholder="Contoh: FT-TK" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="short_name">Nama Singkat / Singkatan</label>
                <input type="text" name="short_name" id="short_name" class="form-control" placeholder="Contoh: Tekkim">
            </div>

            <div class="form-group">
                <label class="form-label" for="type">Jenis Unit *</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="department">Program Studi</option>
                    <option value="faculty">Fakultas</option>
                    <option value="main_library">UPT Perpustakaan Pusat</option>
                    <option value="university">Universitas (Root)</option>
                    <option value="lp">Lembaga Penelitian (LP2M)</option>
                    <option value="upt">Unit Pelaksana Teknis (UPT)</option>
                    <option value="pusat">Pusat Studi</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="parent_id">Unit Induk (Parent)</label>
                <select name="parent_id" id="parent_id" class="form-control">
                    <option value="">-- Tanpa Induk / Root --</option>
                    @foreach($allUnits as $au)
                        <option value="{{ $au->id }}">{{ $au->name }} ({{ $au->code }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="student_count">Jumlah Mahasiswa Aktif</label>
                <input type="number" name="student_count" id="student_count" class="form-control" placeholder="Contoh: 150" min="0">
                <span style="font-size: 0.72rem; color: #475569;">Penting untuk kalkulasi rasio judul buku : mahasiswa pada akreditasi</span>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Contoh: tekkim@univ.ac.id">
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Telepon</label>
                <input type="text" name="phone" id="phone" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label" for="address">Alamat Fisik / Lokasi Ruang</label>
                <textarea name="address" id="address" class="form-control" rows="2"></textarea>
            </div>

            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn-submit" id="btn-submit-text">Simpan Unit</button>
                <button type="button" class="btn-cancel" style="display:none;" id="btn-cancel-edit" onclick="resetForm()">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function editUnit(unit) {
    document.getElementById('form-title').innerText = '✏️ Edit Unit Kerja: ' + unit.name;
    document.getElementById('btn-submit-text').innerText = 'Perbarui Unit';
    
    // Set Action URL
    let form = document.getElementById('unit-form');
    form.action = "{{ url('admin/org-units') }}/" + unit.id;
    document.getElementById('form-method').value = 'PUT';

    // Populate Fields
    document.getElementById('name').value = unit.name || '';
    document.getElementById('code').value = unit.code || '';
    document.getElementById('short_name').value = unit.short_name || '';
    document.getElementById('type').value = unit.type || 'other';
    document.getElementById('parent_id').value = unit.parent_id || '';
    document.getElementById('student_count').value = unit.student_count || '';
    document.getElementById('email').value = unit.email || '';
    document.getElementById('phone').value = unit.phone || '';
    document.getElementById('address').value = unit.address || '';

    // Show cancel button
    document.getElementById('btn-cancel-edit').style.display = 'inline-block';
    
    // Scroll form into view if mobile
    document.getElementById('unit-form').scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('form-title').innerText = '➕ Tambah Unit Kerja Baru';
    document.getElementById('btn-submit-text').innerText = 'Simpan Unit';
    
    let form = document.getElementById('unit-form');
    form.action = "{{ route('admin.org-units.store') }}";
    document.getElementById('form-method').value = 'POST';
    
    form.reset();
    document.getElementById('parent_id').value = '';
    document.getElementById('type').value = 'department';

    document.getElementById('btn-cancel-edit').style.display = 'none';
}
</script>
@endsection
