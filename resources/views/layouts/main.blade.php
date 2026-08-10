<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --app-bg: #f1f5f9;
            --app-surface: #ffffff;
            --app-border: #e2e8f0;
            --app-primary: #4f46e5;
            --app-primary-strong: #4338ca;
            --app-primary-soft: #eef2ff;
            --app-accent: #0ea5e9;
            --app-sidebar: #0f172a;
            --app-sidebar-muted: #94a3b8;
            --app-text: #0f172a;
            --app-muted: #64748b;
            --app-radius: 1rem;
            --app-shadow: 0 1px 3px rgba(15, 23, 42, .06), 0 8px 24px rgba(15, 23, 42, .06);
            --app-shadow-lg: 0 4px 8px rgba(15, 23, 42, .06), 0 20px 48px rgba(15, 23, 42, .12);
        }

        * { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif; }

        body {
            background:
                radial-gradient(1200px 400px at 85% -10%, rgba(79, 70, 229, .08), transparent 60%),
                radial-gradient(900px 380px at -10% 0%, rgba(14, 165, 233, .07), transparent 55%),
                var(--app-bg);
            color: var(--app-text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .app-shell { min-height: 100vh; }

        /* ---------- Sidebar ---------- */
        .app-sidebar {
            width: 280px;
            min-height: 100vh;
            background:
                linear-gradient(180deg, rgba(79, 70, 229, .22) 0%, rgba(14, 165, 233, .10) 40%, rgba(15, 23, 42, 0) 100%),
                var(--app-sidebar);
            color: #fff;
            position: sticky;
            top: 0;
            transition: width .25s cubic-bezier(.4, 0, .2, 1);
            overflow: hidden;
            flex: 0 0 auto;
            z-index: 1040;
        }

        .app-shell.sidebar-collapsed .app-sidebar { width: 92px; }

        .app-brand {
            align-items: center;
            color: #fff;
            display: flex;
            gap: .8rem;
            text-decoration: none;
            font-weight: 700;
            line-height: 1.25;
            min-width: 0;
        }

        .brand-mark {
            align-items: center;
            background: linear-gradient(135deg, #6366f1, #0ea5e9);
            border-radius: 1rem;
            box-shadow: 0 10px 26px rgba(79, 70, 229, .35);
            display: inline-flex;
            flex: 0 0 2.9rem;
            height: 2.9rem;
            justify-content: center;
            width: 2.9rem;
            transition: transform .2s ease;
        }

        .app-brand:hover .brand-mark { transform: rotate(-6deg) scale(1.04); }

        .app-brand small {
            color: var(--app-sidebar-muted);
            display: block;
            font-weight: 500;
            margin-top: .2rem;
        }

        .sidebar-link {
            align-items: center;
            color: #cbd5e1;
            border-radius: .8rem;
            display: flex;
            gap: .8rem;
            font-weight: 500;
            padding: .72rem 1rem;
            position: relative;
            text-decoration: none;
            white-space: nowrap;
            transition: background .15s ease, color .15s ease, transform .15s ease;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, .07);
            color: #fff;
            transform: translateX(2px);
        }

        .sidebar-icon {
            align-items: center;
            background: rgba(255, 255, 255, .09);
            border-radius: .6rem;
            display: inline-flex;
            flex: 0 0 2.1rem;
            height: 2.1rem;
            justify-content: center;
            transition: background .15s ease, color .15s ease;
            width: 2.1rem;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(99, 102, 241, .30), rgba(14, 165, 233, .18));
            color: #fff;
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 20%;
            bottom: 20%;
            width: 4px;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(180deg, #818cf8, #38bdf8);
        }

        .sidebar-link.active .sidebar-icon {
            background: #fff;
            color: var(--app-primary);
        }

        .sidebar-section-label {
            color: rgba(255, 255, 255, .45);
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            padding: .1rem .25rem;
            text-transform: uppercase;
        }

        .sidebar-toggle {
            border-color: rgba(255, 255, 255, .2);
            color: #fff;
            flex: 0 0 auto;
            height: 2.3rem;
            width: 2.3rem;
        }
        .sidebar-toggle:hover { background: rgba(255, 255, 255, .1); color: #fff; }

        .app-shell.sidebar-collapsed .sidebar-text,
        .app-shell.sidebar-collapsed .app-brand small,
        .app-shell.sidebar-collapsed .sidebar-user-meta,
        .app-shell.sidebar-collapsed .sidebar-section-label span { display: none !important; }

        .app-shell.sidebar-collapsed .sidebar-mini-brand { display: inline !important; }

        .app-shell.sidebar-collapsed .app-sidebar { padding-left: 1rem !important; padding-right: 1rem !important; }
        .app-shell.sidebar-collapsed .app-brand { justify-content: center; text-align: center; }
        .app-shell.sidebar-collapsed .brand-mark { flex-basis: 2.5rem; height: 2.5rem; width: 2.5rem; }
        .app-shell.sidebar-collapsed .sidebar-link { justify-content: center; padding-left: .5rem; padding-right: .5rem; }
        .app-shell.sidebar-collapsed .sidebar-toggle { margin-left: auto; margin-right: auto; }
        .app-shell.sidebar-collapsed .sidebar-section-label { justify-content: center; padding: .5rem 0; }

        .sidebar-user-box {
            background: rgba(255, 255, 255, .06);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 1rem;
            padding: .9rem;
        }

        .app-content { flex: 1; min-width: 0; }

        .mobile-topbar { background: var(--app-sidebar); color: #fff; }

        .app-page { padding: 1.75rem 2rem; animation: fadeUp .35s ease both; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ---------- Cards & surfaces ---------- */
        .card {
            border: 1px solid var(--app-border);
            border-radius: var(--app-radius);
            box-shadow: var(--app-shadow);
        }

        .card-header {
            background: var(--app-surface);
            border-bottom: 1px solid var(--app-border);
            border-radius: var(--app-radius) var(--app-radius) 0 0 !important;
        }

        .btn { border-radius: .75rem; font-weight: 600; letter-spacing: .01em; }
        .btn-primary { background: var(--app-primary); border-color: var(--app-primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--app-primary-strong); border-color: var(--app-primary-strong); }
        .btn-outline-primary { color: var(--app-primary); border-color: var(--app-primary); }
        .btn-outline-primary:hover { background: var(--app-primary); border-color: var(--app-primary); }

        .form-control, .form-select {
            border-color: #cbd5e1;
            border-radius: .75rem;
            padding-top: .55rem;
            padding-bottom: .55rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 .25rem rgba(79, 70, 229, .12);
        }

        .badge { font-weight: 600; letter-spacing: .02em; }

        .table > :not(caption) > * > * { padding: .9rem .85rem; }
        .table thead th {
            background: #f8fafc;
            color: var(--app-muted);
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .table tbody tr { transition: background .12s ease; }
        .table tbody tr:hover { background: #f5f7ff; }

        /* ---------- Hero ---------- */
        .app-hero {
            background:
                radial-gradient(600px 220px at 90% 10%, rgba(129, 140, 248, .35), transparent 60%),
                linear-gradient(135deg, #4f46e5 0%, #7c3aed 55%, #0ea5e9 100%);
            color: #fff;
            border: 0;
            box-shadow: 0 18px 44px rgba(79, 70, 229, .28);
            position: relative;
            overflow: hidden;
        }

        .app-hero::after {
            content: '';
            position: absolute;
            right: -80px;
            top: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .app-hero-icon {
            align-items: center;
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .25);
            border-radius: 1rem;
            display: inline-flex;
            height: 3.1rem;
            justify-content: center;
            width: 3.1rem;
            backdrop-filter: blur(6px);
        }

        .stat-tile {
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: .9rem;
            backdrop-filter: blur(8px);
        }

        /* ---------- Score visualization ---------- */
        .score-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.4rem;
            height: 3.4rem;
            border-radius: 50%;
            background: conic-gradient(var(--c, #4f46e5) calc(var(--p, 0) * 1%), #e2e8f0 0);
            position: relative;
        }
        .score-ring::before {
            content: '';
            position: absolute;
            inset: 5px;
            border-radius: 50%;
            background: #fff;
        }
        .score-ring span {
            position: relative;
            z-index: 1;
            font-size: .72rem;
            font-weight: 700;
            color: var(--app-text);
        }

        .score-badge { font-variant-numeric: tabular-nums; }

        .progress { border-radius: .6rem; background: #eef1f6; }
        .progress-bar { background: linear-gradient(90deg, #6366f1, #0ea5e9); border-radius: .6rem; }

        /* ---------- Animations ---------- */
        .hover-lift { transition: transform .18s ease, box-shadow .18s ease; }
        .hover-lift:hover { transform: translateY(-3px); box-shadow: var(--app-shadow-lg); }

        /* ---------- Auth ---------- */
        .auth-wrap { min-height: 100vh; }
        .auth-brand-panel {
            background:
                radial-gradient(700px 300px at 80% 0%, rgba(129, 140, 248, .4), transparent 60%),
                linear-gradient(160deg, #312e81 0%, #4f46e5 50%, #0ea5e9 100%);
            color: #fff;
        }

        .admin-pagination svg { width: 1rem; height: 1rem; }
        .admin-pagination nav { display: flex; justify-content: center; }
        .admin-pagination .pagination { margin-bottom: 0; }

        /* ---------- Toast ---------- */
        .toast { border-radius: .85rem; box-shadow: var(--app-shadow-lg); border: 0; }

        @media (max-width: 991.98px) {
            .app-shell { display: block !important; }
            .app-sidebar { display: none !important; }
            .app-page { padding: 1rem; }
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
                        <span class="sidebar-text">IdeUsahaKu</span>
                        <span class="d-none sidebar-mini-brand">IU</span>
                        <small>Rekomendasi Usaha Mikro</small>
                    </span>
                </a>
                <button class="btn btn-sm sidebar-toggle" type="button" id="sidebarToggle" title="Minimize sidebar" aria-label="Minimize sidebar">
                    <i class="bi bi-layout-sidebar-inset" id="sidebarToggleIcon"></i>
                </button>
            </div>

            <nav class="d-grid gap-2">
                <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}" title="Dashboard">
                    <span class="sidebar-icon"><i class="bi bi-house"></i></span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
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
                    <a class="app-brand" href="{{ route('rekomendasi.form') }}">
                        <span class="brand-mark"><i class="bi bi-stars fs-5"></i></span>
                        <span>IdeUsahaKu</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button class="btn btn-danger btn-sm" type="submit">Logout</button>
                    </form>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.dashboard') }}">Home</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('rekomendasi.form') }}">Rekomendasi</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.master.capitals.index') }}">Modal</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.master.categories.index') }}">Kategori</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.master.formula.index') }}">Formula</a>
                    <a class="btn btn-outline-light btn-sm flex-fill" href="{{ route('admin.business-ideas.index') }}">Data</a>
                </div>
            </div>

            <main class="app-page">
                @if (session('status'))
                    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
                        <div class="toast show align-items-center border-0 text-bg-success" role="alert">
                            <div class="d-flex">
                                <div class="toast-body">
                                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                                </div>
                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                        </div>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
@else
    <main>
        @yield('content')
    </main>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
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

        document.querySelectorAll('.toast').forEach(function (t) {
            setTimeout(() => { new bootstrap.Toast(t, { delay: 4000 }).hide(); }, 3000);
        });
    </script>
@endauth
</body>
</html>
