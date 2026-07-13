<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIRITA - OPAC Perpustakaan')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --text-color: #f8fafc;
            --primary: #0d9488; /* Teal / Hijau Tosca */
            --primary-hover: #0f766e;
            --card-bg: rgba(20, 30, 48, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
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
            background: rgba(11, 15, 25, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--text-color);
            text-decoration: none;
            background: linear-gradient(to right, #0d9488, #2dd4bf);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            margin-left: 1.75rem;
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
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
            transition: all 0.3s;
            z-index: 999;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-floating-admin:hover {
            background: var(--primary-hover);
            transform: scale(1.1) rotate(45deg);
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <a href="{{ route('opac.index') }}" class="logo">SIRITA OPAC</a>
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
                        <a href="{{ $url }}" class="{{ $isActive ? 'active' : '' }}" style="{{ $menu->url === '/member' && session('opac_member_id') ? 'color: #2dd4bf; font-weight: 600;' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('opac.index') }}">Cari Buku</a>
                    @if(session('opac_member_id'))
                        <a href="{{ route('opac.member.dashboard') }}" style="color: #a5b4fc; font-weight: 600;">⊙ {{ session('opac_member_name') }}</a>
                    @else
                        <a href="{{ route('opac.member.area') }}">Keanggotaan</a>
                    @endif
                    <a href="{{ route('opac.page', 'bantuan-layanan') }}" {{ Request::is('page/bantuan-layanan') ? 'class=active' : '' }}>Bantuan</a>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 0.5rem; justify-content: center; align-items: center;">
            <p style="margin: 0; font-weight: 600; color: #94a3b8;">{{ $footerAddress ?? 'Perpustakaan SIRITA' }}</p>
            <p style="margin: 0; font-size: 0.8rem; color: #475569;">{{ $footerCopyright ?? '© 2026 SIRITA' }}</p>
            @if(isset($footerSocials) && $footerSocials !== '#')
                <a href="{{ $footerSocials }}" target="_blank" style="color: var(--primary); font-size: 0.8rem; text-decoration: none; margin-top: 0.25rem;">Media Sosial Perpustakaan</a>
            @endif
        </div>
    </footer>

    @auth
        <a href="{{ route('admin.circulation.index') }}" class="btn-floating-admin" title="Kembali ke Panel Admin">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
        </a>
    @endauth
</body>
</html>
