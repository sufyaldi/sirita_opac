@extends('opac.templates.iainpare.layout')
@section('title', 'Buku Tamu — Penghitung Pengunjung Perpustakaan IAIN Parepare')

@section('content')
<style>
    .visitor-hero { text-align: center; margin-bottom: 2.5rem; }
    .visitor-hero h1 { font-size: 2rem; font-weight: 700; margin: 0 0 0.5rem; color: var(--primary); }
    .visitor-hero p { color: #64748b; font-size: 1rem; margin: 0; }

    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2.5rem; }
    .stat-card {
        background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px;
        padding: 1.5rem; text-align: center; transition: transform 0.3s, box-shadow 0.3s;
        border-top: 4px solid var(--primary);
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(21,128,61,0.1); }
    .stat-card .stat-value { font-size: 2.2rem; font-weight: 700; color: var(--primary); display: block; }
    .stat-card .stat-label { color: #64748b; font-size: 0.85rem; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px; }

    .form-section { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem; }
    @media (max-width: 768px) { .form-section { grid-template-columns: 1fr; } }
    .form-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 2rem; }
    .form-card h3 { color: var(--text-color); font-size: 1.15rem; margin: 0 0 1.25rem; padding-bottom: 0.75rem; border-bottom: 2px solid var(--accent); }
    .form-card h3 span { margin-right: 0.5rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: 0.85rem; color: #64748b; margin-bottom: 0.35rem; font-weight: 600; }
    .form-group input, .form-group select {
        width: 100%; padding: 0.7rem 1rem; border: 1px solid var(--border-color); border-radius: 10px;
        background: #fefce8; color: var(--text-color); font-size: 0.95rem; font-family: 'Open Sans', sans-serif; transition: border-color 0.2s; box-sizing: border-box;
    }
    .form-group input:focus, .form-group select:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(21,128,61,0.1); }
    .form-group input::placeholder { color: #94a3b8; }
    .btn-submit { width: 100%; padding: 0.8rem; border: none; border-radius: 10px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; font-family: 'Open Sans', sans-serif; margin-top: 0.5rem; color: #fff; }
    .btn-member { background: linear-gradient(135deg, #15803d, #22c55e); }
    .btn-member:hover { background: linear-gradient(135deg, #166534, #15803d); transform: translateY(-2px); }
    .btn-guest { background: linear-gradient(135deg, #b45309, #d97706); }
    .btn-guest:hover { background: linear-gradient(135deg, #92400e, #b45309); transform: translateY(-2px); }

    .flash-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #15803d; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500; animation: slideIn 0.4s ease-out; }
    .flash-error { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: 500; animation: slideIn 0.4s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    .chart-section { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 2rem; margin-bottom: 2.5rem; }
    .chart-section h3 { color: var(--text-color); margin: 0 0 1.5rem; font-size: 1.1rem; }
    .chart-bars { display: flex; align-items: flex-end; gap: 0.75rem; height: 180px; padding-bottom: 1.75rem; border-bottom: 1px solid var(--border-color); }
    .chart-bar-wrapper { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; }
    .chart-bar-value { color: #64748b; font-size: 0.75rem; margin-bottom: 0.35rem; font-weight: 600; }
    .chart-bar { width: 100%; max-width: 60px; background: linear-gradient(to top, #15803d, #4ade80); border-radius: 8px 8px 0 0; min-height: 6px; transition: height 0.6s ease-out; }
    .chart-bar:hover { opacity: 0.8; }
    .chart-bar-label { color: #94a3b8; font-size: 0.7rem; margin-top: 0.5rem; text-align: center; white-space: nowrap; }

    .recent-section { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; padding: 2rem; }
    .recent-section h3 { color: var(--text-color); margin: 0 0 1.25rem; font-size: 1.1rem; }
    .recent-table { width: 100%; border-collapse: collapse; }
    .recent-table th { text-align: left; color: #fff; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 1rem; background: var(--primary); }
    .recent-table th:first-child { border-radius: 8px 0 0 0; }
    .recent-table th:last-child { border-radius: 0 8px 0 0; }
    .recent-table td { padding: 0.75rem 1rem; color: var(--text-color); font-size: 0.9rem; border-bottom: 1px solid #f1f5f9; }
    .recent-table tr:hover td { background: #f0fdf4; }
    .badge { display: inline-block; padding: 0.2rem 0.65rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .badge-member { background: rgba(21,128,61,0.1); color: var(--primary); }
    .badge-guest { background: rgba(217,119,6,0.1); color: var(--accent); }
</style>

<div class="visitor-hero">
    <h1>📋 Buku Tamu Perpustakaan</h1>
    <p>Silakan catat kehadiran Anda di Perpustakaan IAIN Parepare. Terima kasih!</p>
</div>

@if(session('success'))
    <div class="flash-success">✅ {{ session('success') }}</div>
@endif
@if($errors->any())
    <div class="flash-error">⚠️ {{ $errors->first() }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card"><span class="stat-value">{{ number_format($todayCount) }}</span><span class="stat-label">Hari Ini</span></div>
    <div class="stat-card"><span class="stat-value">{{ number_format($monthCount) }}</span><span class="stat-label">Bulan Ini</span></div>
    <div class="stat-card"><span class="stat-value">{{ number_format($yearCount) }}</span><span class="stat-label">Tahun Ini</span></div>
    <div class="stat-card"><span class="stat-value">{{ number_format($totalCount) }}</span><span class="stat-label">Total Kunjungan</span></div>
</div>

<div class="form-section">
    <div class="form-card">
        <h3><span>🪪</span> Anggota Perpustakaan</h3>
        <form action="{{ route('opac.visitor.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="member_id">ID Anggota / Scan Barcode</label><input type="text" name="member_id" id="member_id" placeholder="Scan atau ketik ID anggota..." autofocus></div>
            <div class="form-group"><label for="institution_member">Asal Institusi (opsional)</label><input type="text" name="institution" id="institution_member" placeholder="cth: IAIN Parepare"></div>
            <input type="hidden" name="visitor_type" value="Anggota Terdaftar">
            <button type="submit" class="btn-submit btn-member">✓ Catat Kunjungan Anggota</button>
        </form>
    </div>
    <div class="form-card">
        <h3><span>👤</span> Tamu / Pengunjung Umum</h3>
        <form action="{{ route('opac.visitor.store') }}" method="POST">
            @csrf
            <div class="form-group"><label for="guest_name">Nama Lengkap</label><input type="text" name="name" id="guest_name" placeholder="Nama pengunjung..." required></div>
            <div class="form-group"><label for="guest_institution">Asal Institusi</label><input type="text" name="institution" id="guest_institution" placeholder="cth: Universitas Hasanuddin"></div>
            <div class="form-group"><label for="guest_type">Jenis Pengunjung</label>
                <select name="visitor_type" id="guest_type"><option value="Tamu / Umum">Tamu / Umum</option><option value="Mahasiswa">Mahasiswa</option><option value="Dosen">Dosen</option><option value="Pelajar">Pelajar</option><option value="Peneliti">Peneliti</option></select>
            </div>
            <button type="submit" class="btn-submit btn-guest">✓ Catat Kunjungan Tamu</button>
        </form>
    </div>
</div>

<div class="chart-section">
    <h3>📊 Statistik Kunjungan 7 Hari Terakhir</h3>
    @php $counts = array_column($chartData, 'count'); $maxChart = count($counts) ? max($counts) : 1; if ($maxChart < 1) $maxChart = 1; @endphp
    <div class="chart-bars">
        @foreach($chartData as $day)
            <div class="chart-bar-wrapper">
                <span class="chart-bar-value">{{ $day['count'] }}</span>
                <div class="chart-bar" style="height: {{ ($day['count'] / $maxChart) * 100 }}%;"></div>
                <span class="chart-bar-label">{{ $day['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>

<div class="recent-section">
    <h3>🕐 10 Pengunjung Terakhir</h3>
    @if($recentVisitors->isEmpty())
        <p style="color: #94a3b8; text-align: center; padding: 2rem 0;">Belum ada data pengunjung.</p>
    @else
        <table class="recent-table">
            <thead><tr><th>No</th><th>Nama</th><th>Institusi</th><th>Jenis</th><th>Waktu</th></tr></thead>
            <tbody>
                @foreach($recentVisitors as $i => $v)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="font-weight: 600;">{{ $v->name }}</td>
                        <td>{{ $v->institution ?? '-' }}</td>
                        <td><span class="badge {{ $v->member_id ? 'badge-member' : 'badge-guest' }}">{{ $v->visitor_type }}</span></td>
                        <td style="font-size: 0.8rem; color: #94a3b8;">{{ $v->created_at->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
