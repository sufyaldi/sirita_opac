<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Punggung Buku - SIRITA</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            margin: 0;
            padding: 20px;
            background-color: white;
            color: black;
        }

        .label-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .spine-label {
            border: 2px solid black;
            width: 150px;
            padding: 10px;
            text-align: center;
            box-sizing: border-box;
            background: white;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .lib-name {
            font-size: 0.75rem;
            font-weight: bold;
            border-bottom: 1px solid black;
            padding-bottom: 5px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .call-number {
            font-size: 1rem;
            font-weight: bold;
            margin: 10px 0;
        }

        .item-code {
            font-size: 0.75rem;
            border-top: 1px dashed black;
            padding-top: 5px;
            margin-top: 5px;
        }

        /* Print stylesheet optimization */
        @media print {
            body {
                padding: 0;
            }
            .spine-label {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="label-grid">
        @foreach($items as $item)
            <div class="spine-label">
                <div class="lib-name">SIRITA LIB</div>
                <div class="call-number">
                    @php
                        // Construct classic spine label: Classification + Author initials + Title initial
                        $class = $item->biblio->classification ?? '000';
                        $authorName = $item->biblio->authors->first()->author_name ?? '';
                        $authorInitials = '';
                        if (!empty($authorName)) {
                            $parts = explode(' ', preg_replace('/[^a-zA-Z ]/', '', $authorName));
                            $lastname = end($parts);
                            $authorInitials = strtoupper(substr($lastname, 0, 3));
                        }
                        $titleInitial = strtolower(substr($item->biblio->title, 0, 1));
                    @endphp
                    {{ $class }}<br>
                    {{ $authorInitials }}<br>
                    {{ $titleInitial }}
                </div>
                <div class="item-code">{{ $item->item_code }}</div>
            </div>
        @endforeach
    </div>

    <script>
        // Automatically trigger print dialog
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
