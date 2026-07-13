<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OPAC IAIN Parepare')</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        /* IAIN Parepare Theme Colors */
        :root {
            --bg-color: #f8fafc;
            --text-color: #1e293b;
            --primary: #15803d; /* Hijau IAIN */
            --primary-hover: #166534;
            --accent: #d97706; /* Gold/Kuning Emas aksen */
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
            background: linear-gradient(135deg, #14532d 0%, #166534 100%);
            border-bottom: 4px solid var(--accent);
            position: relative;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .header-top {
            background: rgba(0,0,0,0.2);
            padding: 0.5rem 2rem;
            font-size: 0.8rem;
            color: #cbd5e1;
            display: flex;
            justify-content: space-between;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1.25rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .logo-title {
            display: flex;
            flex-direction: column;
            color: #ffffff;
        }

        .logo-title h1 {
            font-size: 1.3rem;
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .logo-title span {
            font-size: 0.75rem;
            color: #fbbf24; /* Gold text */
            font-weight: 600;
            text-transform: uppercase;
        }

        .nav-links a {
            color: #f1f5f9;
            text-decoration: none;
            margin-left: 1.5rem;
            font-weight: 600;
            font-size: 0.88rem;
            transition: color 0.2s;
            border-bottom: 2px solid transparent;
            padding-bottom: 0.25rem;
        }

        .nav-links a:hover {
            color: #fbbf24;
            border-bottom-color: #fbbf24;
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
            background: #14532d;
            padding: 2.5rem 2rem;
            text-align: center;
            color: #cbd5e1;
            font-size: 0.85rem;
            border-top: 5px solid var(--accent);
        }

        footer a {
            color: #fbbf24;
            text-decoration: none;
            font-weight: 600;
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
            box-shadow: 0 4px 20px rgba(21, 128, 61, 0.4);
            transition: all 0.3s;
            z-index: 999;
            border: 2px solid var(--accent);
        }

        .btn-floating-admin:hover {
            background: var(--primary-hover);
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-top">
            <span>PERPUSTAKAAN INSTITUT AGAMA ISLAM NEGERI PAREPARE</span>
            <span>Kontak: perpustakaan@iainpare.ac.id</span>
        </div>
        <div class="nav-container">
            <a href="{{ route('opac.index') }}" class="logo-container">
                <span style="font-size: 2.25rem;">🟢</span>
                <div class="logo-title">
                    <h1>OPAC IAIN PAREPARE</h1>
                    <span>Library Automation System</span>
                </div>
            </a>
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
                        <a href="{{ $url }}" class="{{ $isActive ? 'active' : '' }}" style="{{ $menu->url === '/member' && session('opac_member_id') ? 'color: #fbbf24; font-weight: 700;' : '' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                @else
                    <a href="{{ route('opac.index') }}">Beranda</a>
                    @if(session('opac_member_id'))
                        <a href="{{ route('opac.member.dashboard') }}" style="color: #fbbf24; font-weight: 700;">Area Anggota</a>
                    @else
                        <a href="{{ route('opac.member.area') }}">Area Anggota</a>
                    @endif
                    <a href="#">Informasi</a>
                    <a href="#">Bantuan</a>
                @endif
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <div style="max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 0.5rem; justify-content: center; align-items: center;">
            <p style="margin: 0; font-weight: 600; color: #f1f5f9;">{{ $footerAddress ?? 'Perpustakaan SIRITA' }}</p>
            <p style="margin: 0; font-size: 0.8rem; color: #cbd5e1;">{{ $footerCopyright ?? '© 2026 SIRITA' }}</p>
            @if(isset($footerSocials) && $footerSocials !== '#')
                <a href="{{ $footerSocials }}" target="_blank" style="color: #fbbf24; font-size: 0.8rem; text-decoration: none; margin-top: 0.25rem;">Media Sosial Perpustakaan</a>
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
