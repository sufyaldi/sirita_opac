<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Sirkulasi - SIRITA</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 10px;
            background-color: white;
            color: black;
            font-size: 0.9rem;
            width: 300px;
        }

        .header {
            text-align: center;
            border-bottom: 2px double black;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .header h2 {
            margin: 0 0 5px 0;
            font-size: 1.2rem;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 0.8rem;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
            font-size: 0.85rem;
        }

        .info-table td {
            padding: 2px 0;
        }

        .loan-list {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 0.85rem;
        }

        .loan-list th, .loan-list td {
            text-align: left;
            padding: 5px 0;
            border-bottom: 1px dashed black;
        }

        .footer {
            text-align: center;
            border-top: 1px solid black;
            padding-top: 10px;
            margin-top: 20px;
            font-size: 0.8rem;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>SIRITA LIBRARY</h2>
        <p>Struk Transaksi Peminjaman</p>
    </div>

    <table class="info-table">
        <tr>
            <td style="width: 90px;">Tgl Cetak</td>
            <td>: {{ now()->format('Y-m-d H:i') }}</td>
        </tr>
        <tr>
            <td>ID Anggota</td>
            <td>: <strong>{{ $member->member_id }}</strong></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: {{ $member->member_name }}</td>
        </tr>
    </table>

    <table class="loan-list">
        <thead>
            <tr>
                <th>Judul / Eksemplar</th>
                <th style="text-align: right;">Batas Kembali</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activeLoans as $loan)
                <tr>
                    <td>
                        {{ $loan->item->biblio->title ?? 'Buku' }}<br>
                        <span style="font-size: 0.75rem; color: #555;">Code: {{ $loan->item_code }}</span>
                    </td>
                    <td style="text-align: right; vertical-align: bottom;">
                        {{ $loan->due_date->toDateString() }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Harap kembalikan buku tepat waktu.<br>Terima kasih atas kunjungan Anda!</p>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
