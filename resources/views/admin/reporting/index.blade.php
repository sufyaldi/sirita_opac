@extends('layouts.admin')

@section('title', 'Laporan & Statistik - SIRITA Admin')
@section('header_title', 'Laporan & Analytics Perpustakaan')

@section('content')
<!-- Include Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .metric-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 1.5rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
        text-align: center;
    }

    .metric-val {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin: 0.5rem 0;
    }

    .metric-label {
        font-size: 0.9rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .charts-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-bottom: 2.5rem;
    }

    @media (max-width: 900px) {
        .charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
    }

    .chart-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        margin-bottom: 1.5rem;
    }

    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        padding: 2rem;
        border-radius: 18px;
        backdrop-filter: blur(10px);
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .admin-table th, .admin-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }

    .admin-table th {
        color: #94a3b8;
        font-weight: 500;
    }

    .admin-table td {
        color: white;
    }
</style>

<!-- Metric Cards -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-label">Total Judul Koleksi</div>
        <div class="metric-val">{{ $totalBiblio }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Total Eksemplar Fisik</div>
        <div class="metric-val">{{ $totalItems }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Anggota Terdaftar</div>
        <div class="metric-val">{{ $totalMembers }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Peminjaman Aktif</div>
        <div class="metric-val" style="color: #6366f1;">{{ $activeLoans }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Denda Terkumpul (Lunas)</div>
        <div class="metric-val" style="color: #34d399; font-size: 2rem;">Rp {{ number_format($totalFinesPaid, 0, ',', '.') }}</div>
    </div>
    <div class="metric-card">
        <div class="metric-label">Denda Belum Dibayar</div>
        <div class="metric-val" style="color: #f87171; font-size: 2rem;">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</div>
    </div>
</div>

<!-- Charts section -->
<div class="charts-grid">
    <!-- Monthly Loan Trends -->
    <div class="chart-card">
        <div class="chart-title">Tren Transaksi Peminjaman (6 Bulan Terakhir)</div>
        <div style="height: 300px; position: relative;">
            <canvas id="loanTrendChart"></canvas>
        </div>
    </div>

    <!-- Item Status Proportion -->
    <div class="chart-card">
        <div class="chart-title">Status Ketersediaan Buku</div>
        <div style="height: 300px; position: relative; display: flex; justify-content: center;">
            <canvas id="availabilityChart"></canvas>
        </div>
    </div>
</div>

<!-- Top Borrowed Books Table -->
<div class="table-card">
    <div class="chart-title" style="margin-bottom: 1rem;">Top 5 Buku Terpopuler (Paling Banyak Dipinjam)</div>
    <div style="overflow-x: auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 60px; text-align: center;">Peringkat</th>
                    <th>Judul Buku</th>
                    <th style="width: 150px; text-align: center;">Jumlah Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topBooks as $index => $book)
                    <tr>
                        <td style="text-align: center;"><strong>#{{ $index + 1 }}</strong></td>
                        <td><strong>{{ $book->title }}</strong></td>
                        <td style="text-align: center;"><code>{{ $book->borrow_count }} kali</code></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; color: #64748b; padding: 2rem 0;">Belum ada riwayat transaksi peminjaman buku saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart JS Setup Script -->
<script>
    // 1. Setup Monthly Loan Trend Chart
    const trendCtx = document.getElementById('loanTrendChart').getContext('2d');
    const trendMonths = {!! json_encode($loanTrends->pluck('month')) !!};
    const trendTotals = {!! json_encode($loanTrends->pluck('total_loans')) !!};

    new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: trendMonths.length ? trendMonths : ['No Data'],
            datasets: [{
                label: 'Jumlah Transaksi',
                data: trendTotals.length ? trendTotals : [0],
                backgroundColor: 'rgba(99, 102, 241, 0.4)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#94a3b8' },
                    grid: { color: 'rgba(255, 255, 255, 0.05)' }
                },
                x: {
                    ticks: { color: '#94a3b8' },
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 2. Setup Availability Proportion Pie Chart
    const availCtx = document.getElementById('availabilityChart').getContext('2d');
    new Chart(availCtx, {
        type: 'doughnut',
        data: {
            labels: ['Tersedia', 'Dipinjam'],
            datasets: [{
                data: [{{ $availableItems }}, {{ $totalLentItems }}],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.4)', // Green
                    'rgba(239, 68, 68, 0.4)'    // Red
                ],
                borderColor: [
                    'rgba(16, 185, 129, 1)',
                    'rgba(239, 68, 68, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#94a3b8' }
                }
            }
        }
    });
</script>
@endsection
