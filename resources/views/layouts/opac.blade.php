<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRITA - OPAC Perpustakaan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --text-color: #f8fafc;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
            width: 100%;
            box-sizing: border-box;
        }

        footer {
            border-top: 1px solid var(--border-color);
            padding: 2rem;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="{{ route('opac.index') }}" class="logo">SIRITA OPAC</a>
            <div class="nav-links">
                <a href="{{ route('opac.index') }}">Cari Buku</a>
                <a href="#">Keanggotaan</a>
                <a href="#">Bantuan</a>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SIRITA Library Management System. All rights reserved.</p>
    </footer>
</body>
</html>
