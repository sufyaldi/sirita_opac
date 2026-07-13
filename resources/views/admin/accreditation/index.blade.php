@extends('layouts.admin')
@section('title', 'Laporan Koleksi untuk Akreditasi')

@section('content')
<style>
.acc-header { margin-bottom: 2rem; }
.acc-header h1 { font-size: 1.6rem; font-weight: 700; margin: 0 0 0.3rem; }
.acc-header p  { color: #94a3b8; margin: 0; font-size: 0.9rem; }

.unit-tree { display: flex; flex-direction: column; gap: 1rem; }

.unit-card {
    background: rgba(30,41,59,0.7);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 16px;
    overflow: hidden;
    backdrop-filter: blur(10px);
    transition: border-color 0.3s;
}
.unit-card:hover { border-color: rgba(99,102,241,0.4); }

.unit-card-header {
    padding: 1.25rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    user-select: none;
}

.unit-type-badge {
    display: inline-block;
    padding: 0.2rem 0.6rem;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-left: 0.5rem;
}
.badge-main_library { background: rgba(99,102,241,0.2); color: #a5b4fc; }
.badge-faculty      { background: rgba(16,185,129,0.15); color: #6ee7b7; }
.badge-department   { background: rgba(251,191,36,0.12); color: #fbbf24; }
.badge-university   { background: rgba(192,132,252,0.15); color: #d8b4fe; }
.badge-lp, .badge-upt, .badge-pusat { background: rgba(100,116,139,0.2); color: #94a3b8; }

.unit-name { font-size: 1.05rem; font-weight: 600; color: #f1f5f9; }
.unit-code { color: #64748b; font-size: 0.8rem; font-family: monospace; }

.unit-stats {
    display: flex;
    gap: 1.5rem;
    align-items: center;
}
.stat-pill {
    text-align: center;
}
.stat-pill .val { font-size: 1.3rem; font-weight: 700; color: #818cf8; line-height: 1; }
.stat-pill .lbl { font-size: 0.7rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; }

.btn-report {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.5rem 1rem;
    background: rgba(99,102,241,0.15);
    border: 1px solid rgba(99,102,241,0.3);
    color: #a5b4fc;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.3s;
    white-space: nowrap;
}
.btn-report:hover { background: rgba(99,102,241,0.3); color: white; }

.children-list {
    border-top: 1px solid rgba(255,255,255,0.05);
    padding: 0.75rem 1.5rem 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.child-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    background: rgba(15,23,42,0.5);
    border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.05);
}
.child-row:hover { border-color: rgba(99,102,241,0.2); }

.subchild-list {
    margin-left: 2rem;
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
    margin-top: 0.4rem;
}
.subchild-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 0.9rem;
    background: rgba(15,23,42,0.4);
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,0.04);
}

.legend-box {
    background: rgba(30,41,59,0.5);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    align-items: center;
}
.legend-box span { font-size: 0.82rem; color: #64748b; }
</style>

<div class="acc-header">
    <h1>📊 Laporan Koleksi untuk Akreditasi</h1>
    <p>
        Ringkasan jumlah judul dan eksemplar per unit perpustakaan — siap tunjuk ke asesor BAN-PT / LAM / APS
        @if(isset($univNode)) &nbsp;·&nbsp; <strong>{{ $univNode->name }}</strong> @endif
    </p>
</div>

<div class="legend-box">
    <span>Jenis Unit:</span>
    <span class="unit-type-badge badge-university">Universitas</span>
    <span class="unit-type-badge badge-main_library">Perpustakaan Pusat</span>
    <span class="unit-type-badge badge-faculty">Fakultas</span>
    <span class="unit-type-badge badge-department">Program Studi</span>
    <span class="unit-type-badge badge-lp">LP / UPT</span>
</div>

<div class="unit-tree">
    @foreach($units as $root)
        <div class="unit-card">
            <div class="unit-card-header">
                <div>
                    <span class="unit-name">{{ $root->name }}</span>
                    <span class="unit-type-badge badge-{{ $root->type }}">{{ $root->typeLabel() }}</span>
                    <div class="unit-code">{{ $root->code }}</div>
                </div>
                <div class="unit-stats">
                    @if($root->type !== 'university')
                    <div class="stat-pill">
                        <div class="val">{{ number_format($summary[$root->id]['titles'] ?? 0) }}</div>
                        <div class="lbl">Judul</div>
                    </div>
                    <div class="stat-pill">
                        <div class="val" style="color: #6ee7b7;">{{ number_format($summary[$root->id]['items'] ?? 0) }}</div>
                        <div class="lbl">Eksemplar</div>
                    </div>
                    @endif
                    @if($root->type !== 'university')
                        <a href="{{ route('admin.accreditation.report', $root->id) }}" class="btn-report">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            Lihat Laporan
                        </a>
                    @endif
                </div>
            </div>

            @if($root->children->isNotEmpty())
                <div class="children-list">
                    @foreach($root->children->sortBy('sort_order') as $child)
                        <div>
                            <div class="child-row">
                                <div>
                                    <span class="unit-name" style="font-size: 0.95rem;">{{ $child->name }}</span>
                                    <span class="unit-type-badge badge-{{ $child->type }}">{{ $child->typeLabel() }}</span>
                                    <div class="unit-code">{{ $child->code }}
                                        @if($child->student_count)
                                            &nbsp;·&nbsp; {{ number_format($child->student_count) }} mhs
                                        @endif
                                    </div>
                                </div>
                                <div class="unit-stats">
                                    <div class="stat-pill">
                                        <div class="val">{{ number_format($summary[$child->id]['titles'] ?? 0) }}</div>
                                        <div class="lbl">Judul</div>
                                    </div>
                                    <div class="stat-pill">
                                        <div class="val" style="color: #6ee7b7;">{{ number_format($summary[$child->id]['items'] ?? 0) }}</div>
                                        <div class="lbl">Eksemplar</div>
                                    </div>
                                    <a href="{{ route('admin.accreditation.report', $child->id) }}" class="btn-report">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                        Laporan
                                    </a>
                                </div>
                            </div>

                            {{-- Sub-children (Program Studi) --}}
                            @if($child->children->isNotEmpty())
                                <div class="subchild-list">
                                    @foreach($child->children->sortBy('sort_order') as $sub)
                                        <div class="subchild-row">
                                            <div>
                                                <span style="color: #cbd5e1; font-size: 0.9rem;">{{ $sub->name }}</span>
                                                <span class="unit-type-badge badge-{{ $sub->type }}" style="font-size: 0.65rem;">{{ $sub->typeLabel() }}</span>
                                                @if($sub->student_count)
                                                    <span style="color: #475569; font-size: 0.78rem;"> · {{ number_format($sub->student_count) }} mhs</span>
                                                @endif
                                            </div>
                                            <div class="unit-stats">
                                                <div class="stat-pill">
                                                    <div class="val" style="font-size: 1.1rem;">{{ number_format($summary[$sub->id]['titles'] ?? 0) }}</div>
                                                    <div class="lbl">Judul</div>
                                                </div>
                                                <div class="stat-pill">
                                                    <div class="val" style="font-size: 1.1rem; color: #6ee7b7;">{{ number_format($summary[$sub->id]['items'] ?? 0) }}</div>
                                                    <div class="lbl">Eksemplar</div>
                                                </div>
                                                <a href="{{ route('admin.accreditation.report', $sub->id) }}" class="btn-report" style="font-size: 0.8rem; padding: 0.4rem 0.75rem;">
                                                    Laporan
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
