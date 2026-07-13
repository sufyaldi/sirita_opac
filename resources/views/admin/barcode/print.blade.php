<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Eksemplar - SIRITA</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: white;
            color: black;
        }

        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }

        .barcode-card {
            border: 1px dashed #ccc;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            box-sizing: border-box;
            background: white;
        }

        .book-title {
            font-size: 0.8rem;
            font-weight: bold;
            height: 2.4rem;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 5px;
        }

        svg {
            max-width: 100%;
            height: auto;
        }

        /* Print stylesheet optimization */
        @media print {
            body {
                padding: 0;
            }
            .barcode-card {
                border: 1px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="barcode-grid">
        @foreach($items as $item)
            <div class="barcode-card">
                <div class="book-title">{{ $item->biblio->title ?? '-' }}</div>
                <svg class="barcode"
                    jsbarcode-value="{{ $item->item_code }}"
                    jsbarcode-format="CODE128"
                    jsbarcode-textposition="bottom"
                    jsbarcode-width="2"
                    jsbarcode-height="50"
                    jsbarcode-fontSize="14">
                </svg>
            </div>
        @endforeach
    </div>

    <script>
        // Initialize all barcodes
        JsBarcode(".barcode").init();

        // Automatically trigger print dialog
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
