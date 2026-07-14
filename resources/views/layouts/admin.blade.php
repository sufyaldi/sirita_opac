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

        /* Fix Laravel default giant pagination SVGs */
        nav[role="navigation"] svg {
            width: 1.25rem !important;
            height: 1.25rem !important;
            display: inline-block;
        }
        nav[role="navigation"] .flex.justify-between.flex-1 {
            display: none !important; /* Hide redundant tailwind pagination buttons */
        }
        nav[role="navigation"] div:last-child {
            display: flex !important;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
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
            width: 270px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1.2rem;
            box-sizing: border-box;
            overflow-y: auto;
            max-height: 100vh;
            scrollbar-width: thin;
        }

        .brand {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1.8rem;
            padding-left: 0.5rem;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            display: block;
        }

        .menu-section-header {
            font-size: 0.72rem;
            font-weight: 700;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 1.2rem 0 0.5rem 0.5rem;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .menu-item-parent {
            border-radius: 10px;
            overflow: hidden;
            background: transparent;
            transition: background 0.25s;
        }

        .menu-item-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.7rem 0.85rem;
            color: #cbd5e1;
            font-weight: 500;
            font-size: 0.88rem;
            cursor: pointer;
            user-select: none;
            border-radius: 10px;
            transition: all 0.25s;
        }

        .menu-item-header:hover, .menu-item-parent.active-parent > .menu-item-header {
            color: white;
            background: rgba(99, 102, 241, 0.12);
        }

        .menu-item-header-left {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .menu-item-header-left i {
            color: #818cf8;
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
        }

        .caret-icon {
            transition: transform 0.25s;
            color: #64748b;
        }

        /* Submenu styling */
        .sub-menu-items {
            list-style: none;
            padding: 0.25rem 0 0.5rem 2.2rem;
            margin: 0;
            display: none;
            flex-direction: column;
            gap: 0.3rem;
        }

        .sub-menu-items a {
            display: block;
            padding: 0.4rem 0.6rem;
            font-size: 0.82rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 400;
            border-radius: 6px;
            transition: all 0.2s;
            border-left: 2px solid transparent;
        }

        .sub-menu-items a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.03);
            padding-left: 0.8rem;
        }

        .sub-menu-items li.active a {
            color: #818cf8;
            font-weight: 600;
            border-left-color: var(--primary);
            background: rgba(99, 102, 241, 0.08);
            padding-left: 0.8rem;
        }

        /* Non-dropdown menu link */
        .menu-link-single {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.7rem 0.85rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.88rem;
            border-radius: 10px;
            transition: all 0.25s;
        }

        .menu-link-single i {
            color: #818cf8;
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
        }

        .menu-link-single:hover, .menu-item-single-wrap.active .menu-link-single {
            color: white;
            background: rgba(99, 102, 241, 0.12);
        }

        .menu-item-single-wrap.active .menu-link-single {
            border-left: 3px solid var(--primary);
            border-radius: 0 10px 10px 0;
            padding-left: 0.7rem;
        }

        /* Main Content area */
        .main-content {
            flex: 1;
            padding: 2.5rem;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 1.2rem;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 1.75rem;
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
            padding: 0.45rem 0.9rem;
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
            padding: 0.45rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .btn-view-opac:hover {
            background: var(--primary);
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <a href="{{ route('admin.circulation.index') }}" class="brand">SIRITA Admin</a>
        
        {{-- SECTION 1: LAYANAN & OPERASIONAL --}}
        <div class="menu-section-header">Layanan & Operasional</div>
        <ul class="menu-list">
            
            {{-- Sirkulasi Dropdown --}}
            <li class="menu-item-parent {{ Request::routeIs('admin.circulation.*') ? 'active-parent' : '' }}">
                <div class="menu-item-header" onclick="toggleSubMenu(this)">
                    <div class="menu-item-header-left">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Sirkulasi</span>
                    </div>
                    <i class="fas fa-chevron-left caret-icon"></i>
                </div>
                <ul class="sub-menu-items">
                    <li class="{{ Request::routeIs('admin.circulation.index') || Request::routeIs('admin.circulation.transaction') ? 'active' : '' }}">
                        <a href="{{ route('admin.circulation.index') }}">⊙ Transaksi Sirkulasi</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.circulation.fines') ? 'active' : '' }}">
                        <a href="{{ route('admin.circulation.fines') }}">⊙ Kelola Denda</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.circulation.loan-rules.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.circulation.loan-rules.index') }}">⊙ Aturan Peminjaman</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.circulation.quick-return') ? 'active' : '' }}">
                        <a href="{{ route('admin.circulation.quick-return') }}">⊙ Pengembalian Cepat</a>
                    </li>
                </ul>
            </li>

            {{-- Bibliografi / Katalogisasi Dropdown --}}
            <li class="menu-item-parent {{ Request::routeIs('admin.bibliography.*') || Request::routeIs('admin.copy-cataloging.*') || Request::routeIs('admin.barcode.*') || Request::routeIs('admin.marc.*') || Request::routeIs('admin.items.*') ? 'active-parent' : '' }}">
                <div class="menu-item-header" onclick="toggleSubMenu(this)">
                    <div class="menu-item-header-left">
                        <i class="fas fa-book"></i>
                        <span>Katalogisasi</span>
                    </div>
                    <i class="fas fa-chevron-left caret-icon"></i>
                </div>
                <ul class="sub-menu-items">
                    <li class="{{ Request::routeIs('admin.bibliography.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.bibliography.index') }}">⊙ Bibliografi</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.items.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.items.index') }}">⊙ Daftar Eksemplar</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.copy-cataloging.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.copy-cataloging.index') }}">⊙ Copy Cataloging</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.barcode.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.barcode.index') }}">⊙ Cetak Barcode</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.marc.import') ? 'active' : '' }}">
                        <a href="{{ route('admin.marc.import') }}">⊙ MARC Import</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.marc.export') ? 'active' : '' }}">
                        <a href="{{ route('admin.marc.export') }}">⊙ MARC Export</a>
                    </li>
                </ul>
            </li>

            {{-- Keanggotaan Dropdown --}}
            <li class="menu-item-parent {{ Request::routeIs('admin.membership.*') ? 'active-parent' : '' }}">
                <div class="menu-item-header" onclick="toggleSubMenu(this)">
                    <div class="menu-item-header-left">
                        <i class="fas fa-users"></i>
                        <span>Keanggotaan</span>
                    </div>
                    <i class="fas fa-chevron-left caret-icon"></i>
                </div>
                <ul class="sub-menu-items">
                    <li class="{{ Request::routeIs('admin.membership.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.membership.index') }}">⊙ Daftar Anggota</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.membership.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.membership.create') }}">⊙ Tambah Anggota</a>
                    </li>
                </ul>
            </li>

            {{-- Terbitan Berseri --}}
            <li class="menu-item-single-wrap {{ Request::routeIs('admin.serial-control.*') ? 'active' : '' }}">
                <a href="{{ route('admin.serial-control.index') }}" class="menu-link-single">
                    <i class="fas fa-newspaper"></i>
                    <span>Terbitan Berseri</span>
                </a>
            </li>

            {{-- Inventarisasi --}}
            <li class="menu-item-single-wrap {{ Request::routeIs('admin.stock-take.*') ? 'active' : '' }}">
                <a href="{{ route('admin.stock-take.index') }}" class="menu-link-single">
                    <i class="fas fa-tasks"></i>
                    <span>Inventarisasi</span>
                </a>
            </li>

            {{-- Master Data --}}
            <li class="menu-item-single-wrap {{ Request::routeIs('admin.master-file.*') ? 'active' : '' }}">
                <a href="{{ route('admin.master-file.index', 'author') }}" class="menu-link-single">
                    <i class="fas fa-database"></i>
                    <span>Master Data</span>
                </a>
            </li>

            {{-- Pelaporan Dropdown --}}
            <li class="menu-item-parent {{ Request::routeIs('admin.reporting.*') || Request::routeIs('admin.accreditation.*') || Request::routeIs('admin.visitor.*') ? 'active-parent' : '' }}">
                <div class="menu-item-header" onclick="toggleSubMenu(this)">
                    <div class="menu-item-header-left">
                        <i class="fas fa-chart-bar"></i>
                        <span>Pelaporan</span>
                    </div>
                    <i class="fas fa-chevron-left caret-icon"></i>
                </div>
                <ul class="sub-menu-items">
                    <li class="{{ Request::routeIs('admin.reporting.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.reporting.index') }}">⊙ Dashboard Statistik</a>
                    </li>
                    {{-- Load sub-menu dinamis dari Plugins untuk modul reporting --}}
                    @foreach(\App\Plugins\PluginManager::getMenus('reporting') as $pMenu)
                        <li class="{{ Request::routeIs($pMenu['route']) ? 'active' : '' }}">
                            <a href="{{ route($pMenu['route']) }}">⊙ {{ $pMenu['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>

        {{-- SECTION 3: KONFIGURASI & SISTEM --}}
        <div class="menu-section-header">Sistem & Konfigurasi</div>
        <ul class="menu-list">
            {{-- Konten OPAC Dropdown --}}
            <li class="menu-item-parent {{ Request::routeIs('admin.pages.*') || Request::routeIs('admin.menus.*') ? 'active-parent' : '' }}">
                <div class="menu-item-header" onclick="toggleSubMenu(this)">
                    <div class="menu-item-header-left">
                        <i class="fas fa-globe"></i>
                        <span>Konten OPAC</span>
                    </div>
                    <i class="fas fa-chevron-left caret-icon"></i>
                </div>
                <ul class="sub-menu-items">
                    <li class="{{ Request::routeIs('admin.pages.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.pages.index') }}">⊙ Halaman OPAC</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.menus.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.menus.index') }}">⊙ Menu OPAC</a>
                    </li>
                </ul>
            </li>

            {{-- Konfigurasi Sistem Dropdown --}}
            <li class="menu-item-parent {{ Request::routeIs('admin.system.users.*') || Request::routeIs('admin.system.holidays.*') || Request::routeIs('admin.system.plugins.*') || Request::routeIs('admin.setting.*') || Request::routeIs('admin.plugins.slims-migrator.*') || Request::routeIs('admin.org-units.*') ? 'active-parent' : '' }}">
                <div class="menu-item-header" onclick="toggleSubMenu(this)">
                    <div class="menu-item-header-left">
                        <i class="fas fa-cogs"></i>
                        <span>Sistem</span>
                    </div>
                    <i class="fas fa-chevron-left caret-icon"></i>
                </div>
                <ul class="sub-menu-items">
                    <li class="{{ Request::routeIs('admin.system.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.system.users.index') }}">⊙ Pustakawan & Staf</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.org-units.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.org-units.index') }}">⊙ Kelola Unit / Prodi</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.system.holidays.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.system.holidays.index') }}">⊙ Hari Libur</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.system.plugins.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.system.plugins.index') }}">⊙ Daftar Plugin</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.plugins.slims-migrator.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.plugins.slims-migrator.index') }}">⊙ Migrasi SLiMS</a>
                    </li>
                    <li class="{{ Request::routeIs('admin.setting.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.setting.index') }}">⊙ Pengaturan Umum</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header class="admin-header">
            <h1>@yield('header_title', 'Dashboard')</h1>
            <div class="user-info">
                <!-- Odoo-Style Branch Switcher -->
                @if(Auth::check())
                    @php
                        $userBranches = Auth::user()->orgUnits;
                        $activeBranchId = Auth::user()->activeBranchId();
                        $activeBranch = $userBranches->firstWhere('id', $activeBranchId);
                    @endphp
                    @if($userBranches->count() > 0)
                        <div class="branch-switcher-wrapper" style="margin-right: 1rem; display: flex; align-items: center; gap: 0.5rem; background: rgba(30, 41, 59, 0.6); border: 1px solid rgba(255,255,255,0.08); padding: 0.35rem 0.75rem; border-radius: 8px;">
                            <span style="font-size: 0.75rem; color: #64748b; font-weight: 700; text-transform: uppercase;">Unit:</span>
                            <select onchange="window.location.href='?switch_branch=' + this.value" style="background: transparent; border: none; color: #f1f5f9; font-size: 0.85rem; font-weight: 600; outline: none; cursor: pointer; max-width: 180px;">
                                @foreach($userBranches as $ub)
                                    <option value="{{ $ub->id }}" {{ $ub->id == $activeBranchId ? 'selected' : '' }} style="background: #0f172a; color: #cbd5e1;">
                                        {{ $ub->short_name ?: $ub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endif

                <a href="{{ url('/') }}" target="_blank" class="btn-view-opac">
                    <i class="fas fa-external-link-alt"></i> Lihat OPAC
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

    <script>
    function toggleSubMenu(headerEl) {
        const parentEl = headerEl.parentElement;
        const subMenu = parentEl.querySelector('.sub-menu-items');
        const caret = headerEl.querySelector('.caret-icon');
        
        if (subMenu.style.display === 'none' || subMenu.style.display === '') {
            subMenu.style.display = 'flex';
            caret.style.transform = 'rotate(-90deg)';
        } else {
            subMenu.style.display = 'none';
            caret.style.transform = 'rotate(0deg)';
        }
    }

    // Auto-open active dropdown on page load
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.menu-item-parent').forEach(function(parent) {
            const hasActiveChild = parent.querySelector('.sub-menu-items li.active');
            if (hasActiveChild || parent.classList.contains('active-parent')) {
                const subMenu = parent.querySelector('.sub-menu-items');
                const caret = parent.querySelector('.caret-icon');
                if (subMenu) subMenu.style.display = 'flex';
                if (caret) caret.style.transform = 'rotate(-90deg)';
            }
        });
    });
    </script>
    @stack('scripts')
</body>
</html>
