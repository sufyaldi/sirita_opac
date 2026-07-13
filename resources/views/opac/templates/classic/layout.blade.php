<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRITA - OPAC Perpustakaan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Akasia SLiMS Classic Theme Color Palette */
        :root {
            --bg-color: #f1f5f9;
            --text-color: #262930;
            --primary: #105e7c; /* Akasia Blue */
            --primary-hover: #0d4d65;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: var(--primary);
            border-bottom: 3px solid #0b4359;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .nav-links a {
            color: #e5e5e5;
            text-decoration: none;
            margin-left: 1.75rem;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #ffffff;
        }

        main {
            flex: 1;
            max-width: 1200px;
            margin: 0 auto;
            padding: 3.5rem 2rem;
            width: 100%;
            box-sizing: border-box;
        }

        footer {
            background: #262930;
            padding: 2rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.85rem;
            border-top: 4px solid var(--primary);
        }

        .btn-floating-admin {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px rgba(16, 94, 124, 0.4);
            transition: all 0.3s;
            z-index: 999;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-floating-admin:hover {
            background: var(--primary-hover);
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="{{ route('opac.index') }}" class="logo">🏛️ SIRITA OPAC</a>
            <div class="nav-links">
                @if(isset($opacMenus) && $opacMenus->isNotEmpty())
                    @foreach($opacMenus as $menu)
                        @php
                            $url = $menu->url;
                            if ($url === '/') {
                                $isActive = Request::path() === '/';
                            } elseif ($url === '/member') {
                                $isActive = Request::is('member*');
                                if (session('opac_member_id')) {
                                    $url = route('opac.member.dashboard');
                                } else {
                                    $url = route('opac.member.area');
                                }
                            } else {
                                $isActive = false;
                            }
                            
                            $label = $menu->name;
                            if ($menu->url === '/member' && session('opac_member_id')) {
                                $label = '⊙ ' . session('opac_member_name');
                            }
                        @endphp
                        <a href="{{ $url }}" class="{{ $isActive ? 'active' : '' }}" style="{{ $menu->url === '/member' && session('opac_member_id') ? 'color: #38bdf8; font-weight: 700;' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('opac.index') }}">Cari Buku</a>
                    @if(session('opac_member_id'))
                        <a href="{{ route('opac.member.dashboard') }}" style="color: #38bdf8; font-weight: 700;">⊙ Keanggotaan</a>
                    @else
                        <a href="{{ route('opac.member.area') }}">Keanggotaan</a>
                    @endif
                    <a href="#">Bantuan</a>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} SIRITA Library Management System. Tema Akasia Classic. All rights reserved.</p>
    </footer>

    @auth
        <a href="{{ route('admin.circulation.index') }}" class="btn-floating-admin" title="Kembali ke Panel Admin">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        </a>
    @endauth
</body>
</html>
