<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --app-bg: #f4f7fb;
            --app-surface: #ffffff;
            --app-border: #dbe4ef;
            --app-primary: #2563eb;
            --app-primary-soft: #e8f0ff;
            --app-sidebar: #111827;
            --app-sidebar-muted: #9ca3af;
        }

        body {
            background: var(--app-bg);
            color: #1f2937;
        }

        .app-shell {
            min-height: 100vh;
        }

        .app-sidebar {
            width: 280px;
            min-height: 100vh;
            background:
                linear-gradient(180deg, rgba(37, 99, 235, .18) 0%, rgba(8, 145, 178, .08) 42%, rgba(17, 24, 39, 0) 100%),
                var(--app-sidebar);
            color: #fff;
            position: sticky;
            top: 0;
            transition: width .2s ease;
            overflow: hidden;
            flex: 0 0 auto;
        }

        .app-shell.sidebar-collapsed .app-sidebar {
            width: 88px;
        }

        .app-brand {
            align-items: center;
            color: #fff;
            display: flex;
            gap: .75rem;
            text-decoration: none;
            font-weight: 700;
            line-height: 1.2;
            min-width: 0;
        }

        .brand-mark {
            align-items: center;
            background: linear-gradient(135deg, #60a5fa, #22d3ee);
            border-radius: 1rem;
            box-shadow: 0 12px 30px rgba(34, 211, 238, .22);
            display: inline-flex;
            flex: 0 0 2.75rem;
            height: 2.75rem;
            justify-content: center;
            width: 2.75rem;
        }

        .app-brand small {
            color: var(--app-sidebar-muted);
            display: block;
            font-weight: 400;
            margin-top: .25rem;
        }

        .sidebar-link {
            align-items: center;
            color: #d1d5db;
            border-radius: .75rem;
            display: flex;
            gap: .75rem;
            font-weight: 500;
            padding: .75rem 1rem;
            position: relative;
            text-decoration: none;
            white-space: nowrap;
        }

        .sidebar-icon {
            align-items: center;
            background: rgba(255, 255, 255, .1);
            border-radius: .55rem;
            display: inline-flex;
            flex: 0 0 2rem;
            height: 2rem;
            justify-content: center;
            transition: background .2s ease, color .2s ease;
            width: 2rem;
        }

        .sidebar-link:hover,
        .sidebar-link.active {
            background: rgba(255, 255, 255, .12);
            color: #fff;
        }

        .sidebar-link.active .sidebar-icon {
            background: #fff;
            color: var(--app-primary);
        }

        .sidebar-user {
            border-top: 1px solid rgba(255, 255, 255, .12);
            color: var(--app-sidebar-muted);
            margin-top: auto;
            padding-top: 1rem;
        }

        .sidebar-user-box {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            border-radius: 1rem;
            padding: .9rem;
        }

        .sidebar-section-label {
            color: rgba(255, 255, 255, .55);
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            padding: .15rem .25rem;
            text-transform: uppercase;
        }

        .sidebar-toggle {
            border-color: rgba(255, 255, 255, .22);
            color: #fff;
            flex: 0 0 auto;
            height: 2.25rem;
            width: 2.25rem;
        }

        .app-shell.sidebar-collapsed .sidebar-text,
        .app-shell.sidebar-collapsed .app-brand small,
        .app-shell.sidebar-collapsed .sidebar-user-meta {
            display: none !important;
        }

        .app-shell.sidebar-collapsed .sidebar-mini-brand {
            display: inline !important;
        }

        .app-shell.sidebar-collapsed .app-sidebar {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .app-shell.sidebar-collapsed .app-brand {
            text-align: center;
        }

        .app-shell.sidebar-collapsed .brand-mark {
            flex-basis: 2.5rem;
            height: 2.5rem;
            width: 2.5rem;
        }

        .app-shell.sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding-left: .5rem;
            padding-right: .5rem;
        }

        .app-shell.sidebar-collapsed .sidebar-toggle {
            margin-left: auto;
            margin-right: auto;
        }

        .app-content {
            flex: 1;
            min-width: 0;
        }

        .mobile-topbar {
            background: var(--app-sidebar);
            color: #fff;
        }

        .app-page {
            padding: 2rem;
        }

        .app-hero {
            background: linear-gradient(135deg, #2563eb 0%, #0891b2 100%);
            color: #fff;
            border: 0;
            box-shadow: 0 14px 36px rgba(37, 99, 235, .20);
        }

        .app-hero-icon {
            align-items: center;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 1rem;
            display: inline-flex;
            height: 3rem;
            justify-content: center;
            width: 3rem;
        }

        .card {
            border: 1px solid var(--app-border);
            border-radius: .85rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, .05);
        }

        .card-header {
            border-bottom-color: var(--app-border);
            border-radius: .85rem .85rem 0 0 !important;
        }

        .btn-primary {
            background: var(--app-primary);
            border-color: var(--app-primary);
        }

        .btn {
            border-radius: .7rem;
            font-weight: 600;
        }

        .form-control,
        .form-select {
            border-color: #cbd5e1;
            border-radius: .7rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 .25rem rgba(37, 99, 235, .12);
        }

        .table > :not(caption) > * > * {
            padding: 1rem .85rem;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-size: .78rem;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .table tbody tr:hover {
            background: #f8fbff;
        }

        .score-badge { font-variant-numeric: tabular-nums; }
        .admin-pagination svg { width: 1rem; height: 1rem; }
        .admin-pagination nav { display: flex; justify-content: center; }
        .admin-pagination .pagination { margin-bottom: 0; }

        @media (max-width: 991.98px) {
            .app-shell {
                display: block !important;
            }

            .app-sidebar {
                display: none !important;
            }

            .app-page {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
@auth
    <div class="app-shell d-flex" id="appShell">
        <aside class="app-sidebar d-flex flex-column p-4">
            <div class="d-flex align-items-start justify-content-between gap-2 mb-4">
                <a class="app-brand" href="{{ route('rekomendasi.form') }}">
                    <span class="brand-mark"><i class="bi bi-stars fs-4"></i></span>
                    <span>
                        <span class="sidebar-text">Rekomendasi Usaha Mikro</span>
                        <span class="d-none sidebar-mini-brand">RUM</span>
                        <small>Weighted Product Method</small>
                    </span>
                </a>
                <button class="btn btn-sm sidebar-toggle" type="button" id="sidebarToggle" title="Minimize sidebar" aria-label="Minimize sidebar">
                    <i class="bi bi-layout-sidebar-inset" id="sidebarToggleIcon"></i>
                </button>
            </div>

            <nav class="d-grid gap-2">
                <a class="sidebar-link {{ request()->routeIs('rekomendasi.*') ? 'active' : '' }}" href="{{ route('rekomendasi.form') }}" title="Rekomendasi">
                    <span class="sidebar-icon"><i class="bi bi-graph-up-arrow"></i></span>
                    <span class="sidebar-text">Rekomendasi</span>
                </a>
                <div class="mt-2 mb-1 d-flex align-items-center gap-2 sidebar-section-label">
                    <i class="bi bi-folder2-open"></i>
                    <span>Master</span>
                </div>
                <a class="sidebar-link {{ request()->routeIs('admin.master.capitals.*') ? 'active' : '' }}" href="{{ route('admin.master.capitals.index') }}" title="Modal Usaha">
                    <span class="sidebar-icon"><i class="bi bi-cash-coin"></i></span>
                    <span class="sidebar-text">Modal Usaha</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.master.categories.*') ? 'active' : '' }}" href="{{ route('admin.master.categories.index') }}" title="Kategori Usaha">
                    <span class="sidebar-icon"><i class="bi bi-tags"></i></span>
                    <span class="sidebar-text">Kategori Usaha</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.master.times.*') ? 'active' : '' }}" href="{{ route('admin.master.times.index') }}" title="Waktu Luang">
                    <span class="sidebar-icon"><i class="bi bi-clock"></i></span>
                    <span class="sidebar-text">Waktu Luang</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.master.formula.*') ? 'active' : '' }}" href="{{ route('admin.master.formula.index') }}" title="Formula">
                    <span class="sidebar-icon"><i class="bi bi-sliders"></i></span>
                    <span class="sidebar-text">Formula</span>
                </a>
                <a class="sidebar-link {{ request()->routeIs('admin.business-ideas.*') ? 'active' : '' }}" href="{{ route('admin.business-ideas.index') }}" title="Data Usaha">
                    <span class="sidebar-icon"><i class="bi bi-database-check"></i></span>
                    <span class="sidebar-text">Data Usaha</span>
                </a>
            </nav>

            <div class="sidebar-user">
                <div class="sidebar-user-box mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="sidebar-icon"><i class="bi bi-person-circle"></i></span>
                        <div class="sidebar-user-meta">
                            <div class="small">Login sebagai</div>
                            <div class="fw-semibold text-white">{{ auth()->user()->name }}</div>
                        </div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button class="btn btn-danger w-100" type="submit">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="app-content">
            <div class="mobile-topbar d-lg-none p-3">
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <a class="app-brand" href="{{ route('rekomendasi.form') }}">Rekomendasi Usaha</a>
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">Logout</button>
                    </form>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('rekomendasi.form') }}">Rekomendasi</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.master.capitals.index') }}">Modal</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.master.categories.index') }}">Kategori</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.master.formula.index') }}">Formula</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.business-ideas.index') }}">Data</a>
                </div>
            </div>

            <main class="app-page">
                @yield('content')
            </main>
        </div>
    </div>
@else
    <main class="py-5">
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@auth
    <script>
        const appShell = document.getElementById('appShell');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');
        const collapsedKey = 'sidebar-collapsed';

        function setSidebarState(isCollapsed) {
            appShell.classList.toggle('sidebar-collapsed', isCollapsed);
            sidebarToggleIcon.className = isCollapsed ? 'bi bi-layout-sidebar-inset-reverse' : 'bi bi-layout-sidebar-inset';
            sidebarToggle.setAttribute('title', isCollapsed ? 'Expand sidebar' : 'Minimize sidebar');
            sidebarToggle.setAttribute('aria-label', isCollapsed ? 'Expand sidebar' : 'Minimize sidebar');
            localStorage.setItem(collapsedKey, isCollapsed ? '1' : '0');
        }

        setSidebarState(localStorage.getItem(collapsedKey) === '1');

        sidebarToggle.addEventListener('click', function () {
            setSidebarState(!appShell.classList.contains('sidebar-collapsed'));
        });
    </script>
@endauth
</body>
</html>
