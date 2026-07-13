@extends('layouts.admin')
@section('title', 'Laporan Koleksi – ' . $unit->name)

@section('content')
<style>
.report-header { margin-bottom: 1.5rem; }
.report-header .back-link {
    display: inline-flex; align-items: center; gap: 0.4rem;
    color: #64748b; text-decoration: none; font-size: 0.85rem;
    margin-bottom: 0.75rem; transition: color 0.2s;
}
.report-header .back-link:hover { color: #a5b4fc; }
.report-header h1 { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem; color: #f1f5f9; }
.report-header .sub { color: #64748b; font-size: 0.88rem; }

/* Metric Cards */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.metric-card {
    background: rgba(30,41,59,0.8);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 1.25rem;
    text-align: center;
    transition: all 0.3s;
}
.metric-card:hover { border-color: rgba(99,102,241,0.3); transform: translateY(-2px); }
.metric-card .metric-val {
    font-size: 2rem; font-weight: 800; line-height: 1;
    margin-bottom: 0.35rem;
}
.metric-card .metric-lbl {
    font-size: 0.75rem; color: #64748b;
    text-transform: uppercase; letter-spacing: 0.05em;
}
.metric-card .metric-sub {
    font-size: 0.82rem; color: #475569; margin-top: 0.2rem;
}
.c-indigo { color: #818cf8; }
.c-green  { color: #6ee7b7; }
.c-amber  { color: #fbbf24; }
.c-sky    { color: #7dd3fc; }
.c-pink   { color: #f9a8d4; }
.c-teal   { color: #2dd4bf; }

/* Ratio Card */
.ratio-card {
    background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(16,185,129,0.1));
    border: 1px solid rgba(99,102,241,0.25);
    border-radius: 14px;
    padding: 1.25rem;
    text-align: center;
}
.ratio-card .ratio-val { font-size: 2rem; font-weight: 800; color: #a5b4fc; line-height: 1; }
.ratio-card .ratio-note { font-size: 0.75rem; color: #64748b; margin-top: 0.25rem; }

/* Section */
.section { margin-bottom: 1.75rem; }
.section-title {
    font-size: 0.9rem; font-weight: 600; color: #94a3b8;
    text-transform: uppercase; letter-spacing: 0.07em;
    margin-bottom: 0.75rem;
    padding-bottom: 0.4rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

/* Bar chart */
.ddc-row { margin-bottom: 0.6rem; }
.ddc-row .ddc-label { display: flex; justify-content: space-between; margin-bottom: 0.2rem; font-size: 0.82rem; color: #cbd5e1; }
.ddc-bar-wrap { height: 8px; background: rgba(255,255,255,0.05); border-radius: 99px; overflow: hidden; }
.ddc-bar { height: 100%; border-radius: 99px; background: linear-gradient(90deg, #6366f1, #8b5cf6); transition: width 0.8s ease; }

/* Title Table */
.title-table { width: 100%; border-collapse: collapse; font-size: 0.84rem; }
.title-table th { text-align: left; padding: 0.6rem 0.75rem; color: #64748b; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.07); }
.title-table td { padding: 0.6rem 0.75rem; color: #cbd5e1; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: top; }
.title-table tr:hover td { background: rgba(99,102,241,0.05); }
.badge-core { display: inline-block; padding: 0.15rem 0.45rem; background: rgba(251,191,36,0.15); color: #fbbf24; border-radius: 5px; font-size: 0.7rem; font-weight: 600; }
.badge-noncore { display: inline-block; padding: 0.15rem 0.45rem; background: rgba(100,116,139,0.15); color: #64748b; border-radius: 5px; font-size: 0.7rem; }

.card-section {
    background: rgba(15,23,42,0.5);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 14px;
    padding: 1.25rem;
}

.print-btn {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 0.6rem 1.2rem;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white; border-radius: 10px; text-decoration: none;
    font-size: 0.88rem; font-weight: 600; cursor: pointer; border: none;
    transition: opacity 0.2s;
}
.print-btn:hover { opacity: 0.85; }

@media print {
    .no-print { display: none !important; }
    body { background: white; color: black; }
    .card-section { border: 1px solid #ddd; }
}
</style>

<div class="report-header">
    <a href="{{ route('admin.accreditation.index') }}" class="back-link no-print">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke Daftar Unit
    </a>
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h1>📚 {{ $unit->name }}</h1>
            <div class="sub">
                {{ $unit->typeLabel() }}
                @if($unit->parent) · {{ $unit->parent->name }} @endif
                @if($unit->student_count) · {{ number_format($unit->student_count) }} mahasiswa aktif @endif
            </div>
        </div>
        <button onclick="window.print()" class="print-btn no-print">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Cetak / PDF
        </button>
    </div>
</div>

{{-- Metric Cards --}}
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-val c-indigo">{{ number_format($totalTitles) }}</div>
        <div class="metric-lbl">Total Judul</div>
        <div class="metric-sub">Buku unik</div>
    </div>
    <div class="metric-card">
        <div class="metric-val c-green">{{ number_format($totalItems) }}</div>
        <div class="metric-lbl">Total Eksemplar</div>
        <div class="metric-sub">Fisik di unit</div>
    </div>
    <div class="metric-card">
        <div class="metric-val c-amber">{{ number_format($coreTitles) }}</div>
        <div class="metric-lbl">Buku Inti</div>
        <div class="metric-sub">{{ $corePct }}% dari total</div>
    </div>
    <div class="metric-card">
        <div class="metric-val c-sky">{{ number_format($recentTitles) }}</div>
        <div class="metric-lbl">Terbaru ≤10 Thn</div>
        <div class="metric-sub">{{ $recentPct }}% dari total</div>
    </div>
    <div class="metric-card">
        <div class="metric-val c-pink">{{ number_format($foreignTitles) }}</div>
        <div class="metric-lbl">Koleksi Asing</div>
        <div class="metric-sub">{{ $foreignPct }}% dari total</div>
    </div>
    <div class="ratio-card">
        @if($ratio)
            <div class="ratio-val">1 : {{ $ratio }}</div>
            <div class="ratio-note">Rasio Judul : Mahasiswa</div>
        @else
            <div class="ratio-val" style="font-size: 1.2rem; color: #475569;">N/A</div>
            <div class="ratio-note">Data mahasiswa belum diisi</div>
        @endif
    </div>
</div>

{{-- Progress bars / indicators --}}
<div class="section card-section" style="margin-bottom: 1.5rem;">
    <div class="section-title">Komposisi Koleksi</div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
            <div class="ddc-row">
                <div class="ddc-label"><span>Buku Terbaru (≤10 thn)</span><span>{{ $recentPct }}%</span></div>
                <div class="ddc-bar-wrap"><div class="ddc-bar" style="width: {{ $recentPct }}%; background: linear-gradient(90deg, #0ea5e9, #6366f1);"></div></div>
            </div>
            <div class="ddc-row" style="margin-top: 0.75rem;">
                <div class="ddc-label"><span>Buku Inti Kurikulum</span><span>{{ $corePct }}%</span></div>
                <div class="ddc-bar-wrap"><div class="ddc-bar" style="width: {{ $corePct }}%; background: linear-gradient(90deg, #f59e0b, #f97316);"></div></div>
            </div>
        </div>
        <div>
            <div class="ddc-row">
                <div class="ddc-label"><span>Koleksi Berbahasa Asing</span><span>{{ $foreignPct }}%</span></div>
                <div class="ddc-bar-wrap"><div class="ddc-bar" style="width: {{ $foreignPct }}%; background: linear-gradient(90deg, #ec4899, #8b5cf6);"></div></div>
            </div>
            <div class="ddc-row" style="margin-top: 0.75rem;">
                <div class="ddc-label">
                    <span>Standar BAN-PT (≥ 200 judul)</span>
                    <span style="color: {{ $totalTitles >= 200 ? '#6ee7b7' : '#f87171' }}">
                        {{ $totalTitles >= 200 ? '✓ Memenuhi' : '✗ Belum' }}
                    </span>
                </div>
                <div class="ddc-bar-wrap"><div class="ddc-bar" style="width: {{ min(100, $totalTitles/200*100) }}%; background: linear-gradient(90deg, {{ $totalTitles >= 200 ? '#10b981, #6366f1' : '#ef4444, #f97316' }});"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- DDC Breakdown --}}
@if($ddcBreakdown->isNotEmpty())
<div class="section card-section" style="margin-bottom: 1.5rem;">
    <div class="section-title">Distribusi Klasifikasi DDC (Top 10)</div>
    @php $maxDdc = $ddcBreakdown->first()->total ?? 1; @endphp
    @foreach($ddcBreakdown as $ddc)
        <div class="ddc-row">
            <div class="ddc-label">
                <span>{{ $ddc->ddc_code }} — {{ $ddc->total }} judul</span>
                <span>{{ round($ddc->total / max($totalTitles,1) * 100, 1) }}%</span>
            </div>
            <div class="ddc-bar-wrap">
                <div class="ddc-bar" style="width: {{ round($ddc->total / $maxDdc * 100) }}%;"></div>
            </div>
        </div>
    @endforeach
</div>
@endif

{{-- Year Breakdown --}}
@if($yearBreakdown->isNotEmpty())
<div class="section card-section" style="margin-bottom: 1.5rem;">
    <div class="section-title">Distribusi Tahun Terbit (15 Tahun Terakhir)</div>
    @php $maxYear = $yearBreakdown->max('total') ?: 1; @endphp
    @foreach($yearBreakdown as $yr)
        <div class="ddc-row">
            <div class="ddc-label">
                <span>{{ $yr->year }}</span>
                <span>{{ $yr->total }} judul</span>
            </div>
            <div class="ddc-bar-wrap">
                <div class="ddc-bar" style="width: {{ round($yr->total / $maxYear * 100) }}%;
                    background: linear-gradient(90deg,
                        {{ $yr->year >= date('Y') - 5 ? '#10b981, #6366f1' : ($yr->year >= date('Y') - 10 ? '#6366f1, #8b5cf6' : '#475569, #64748b') }});"></div>
            </div>
        </div>
    @endforeach
</div>
@endif

{{-- Daftar Lengkap Judul --}}
<div class="section">
    <div class="section-title" style="display: flex; justify-content: space-between; align-items: center;">
        <span>Daftar Lengkap Judul ({{ number_format($totalTitles) }} judul total)</span>
        <span style="color: #475569; font-size: 0.75rem; font-weight: normal; text-transform: none;">
            Halaman {{ $titleList->currentPage() }} dari {{ $titleList->lastPage() }}
        </span>
    </div>
    <div class="card-section" style="padding: 0; overflow-x: auto;">
        <table class="title-table">
            <thead>
                <tr>
                    <th width="40">No</th>
                    <th>Judul Buku</th>
                    <th width="90">Klasifikasi</th>
                    <th width="70">Tahun</th>
                    <th width="60">Eks.</th>
                    <th width="80">Jenis</th>
                </tr>
            </thead>
            <tbody>
                @forelse($titleList as $i => $title)
                    <tr>
                        <td style="color: #475569;">{{ $titleList->firstItem() + $i }}</td>
                        <td>
                            <a href="{{ route('admin.bibliography.show', $title->biblio_id) }}"
                               style="color: #c7d2fe; text-decoration: none; font-weight: 500;"
                               target="_blank">
                                {{ Str::limit($title->title, 80) }}
                            </a>
                            @if($title->isbn_issn)
                                <div style="font-size: 0.75rem; color: #475569;">ISBN: {{ $title->isbn_issn }}</div>
                            @endif
                        </td>
                        <td style="font-family: monospace; color: #7dd3fc;">{{ $title->classification ?: '–' }}</td>
                        <td style="color: {{ ($title->publish_year && $title->publish_year >= date('Y') - 10) ? '#6ee7b7' : '#94a3b8' }};">
                            {{ $title->publish_year ?: '–' }}
                        </td>
                        <td style="text-align: center; font-weight: 700; color: {{ $title->copies > 0 ? '#a5b4fc' : '#ef4444' }};">
                            {{ $title->copies }}
                        </td>
                        <td>
                            @if($title->is_core)
                                <span class="badge-core">Inti</span>
                            @else
                                <span class="badge-noncore">Penunjang</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #475569; padding: 2rem;">
                            Belum ada koleksi yang ditandai untuk unit ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="margin-top: 1rem;" class="no-print">
        {{ $titleList->links() }}
    </div>
</div>
@endsection
