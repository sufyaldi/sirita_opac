<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - SIRITA')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --sidebar-bg: #111827;
            --text-color: #f8fafc;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --border-color: rgba(255, 255, 255, 0.08);
            --card-bg: rgba(17, 24, 39, 0.6);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
            box-sizing: border-box;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2.5rem;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            padding: 0.8rem 1rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .menu-item a:hover, .menu-item.active a {
            color: white;
            background: rgba(99, 102, 241, 0.15);
        }

        .menu-item.active a {
            border-left: 3px solid var(--primary);
            border-radius: 0 12px 12px 0;
        }

        /* Main Content area */
        .main-content {
            flex: 1;
            padding: 3rem;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.5rem;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--border-color);
            color: #f87171;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        .btn-view-opac {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(99, 102, 241, 0.15);
            border: 1px solid var(--primary);
            color: white;
            padding: 0.5rem 1.1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .btn-view-opac:hover {
            background: var(--primary);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="#" class="brand">SIRITA Admin</a>
        <ul class="menu-list">
            <li class="menu-item {{ Request::routeIs('admin.circulation.*') ? 'active' : '' }}">
                <a href="{{ route('admin.circulation.index') }}">Sirkulasi</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.circulation.fines') ? 'active' : '' }}">
                <a href="{{ route('admin.circulation.fines') }}">Kelola Denda</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.bibliography.*') ? 'active' : '' }}">
                <a href="{{ route('admin.bibliography.index') }}">Bibliografi</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.membership.*') ? 'active' : '' }}">
                <a href="{{ route('admin.membership.index') }}">Keanggotaan</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.master-file.*') ? 'active' : '' }}">
                <a href="{{ route('admin.master-file.index', 'author') }}">Master Data</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.copy-cataloging.*') ? 'active' : '' }}">
                <a href="{{ route('admin.copy-cataloging.index') }}">Copy Cataloging</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.barcode.*') ? 'active' : '' }}">
                <a href="{{ route('admin.barcode.index') }}">Cetak Barcode</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.marc.import') ? 'active' : '' }}">
                <a href="{{ route('admin.marc.import') }}">MARC Import</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.marc.export') ? 'active' : '' }}">
                <a href="{{ route('admin.marc.export') }}">MARC Export</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.reporting.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reporting.index') }}">Pelaporan</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.serial-control.*') ? 'active' : '' }}">
                <a href="{{ route('admin.serial-control.index') }}">Terbitan Berseri</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.stock-take.*') ? 'active' : '' }}">
                <a href="{{ route('admin.stock-take.index') }}">Inventarisasi</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.accreditation.*') ? 'active' : '' }}" style="{{ Request::routeIs('admin.accreditation.*') ? 'background: linear-gradient(90deg, rgba(99,102,241,0.15), transparent); border-left: 2px solid #818cf8;' : '' }}">
                <a href="{{ route('admin.accreditation.index') }}" style="display:flex;align-items:center;gap:0.5rem;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    Akreditasi
                    <span style="margin-left:auto;font-size:0.65rem;background:rgba(251,191,36,0.2);color:#fbbf24;padding:0.1rem 0.4rem;border-radius:4px;font-weight:700;">BAN-PT</span>
                </a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.system.holidays.*') ? 'active' : '' }}">
                <a href="{{ route('admin.system.holidays.index') }}">Hari Libur</a>
            </li>

            <li class="menu-item {{ Request::routeIs('admin.system.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.system.users.index') }}">Pustakawan Staf</a>
            </li>
            <li class="menu-item {{ Request::routeIs('admin.setting.*') ? 'active' : '' }}">
                <a href="{{ route('admin.setting.index') }}">Pengaturan</a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h1>@yield('header_title', 'Dashboard')</h1>
            <div class="user-info">
                <a href="{{ url('/') }}" target="_blank" class="btn-view-opac">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 0.1rem;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                    Lihat OPAC
                </a>
                <span>Halo, <strong>{{ Auth::user()->realname ?? 'Admin' }}</strong></span>
                <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        </header>

        @if(session('success'))
            <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #34d399; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 1rem; border-radius: 12px; margin-bottom: 2rem;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
