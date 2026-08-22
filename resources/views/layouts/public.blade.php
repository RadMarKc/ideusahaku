<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'IdeUsahaKu')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
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
            --app-accent: #0ea5e9;
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

        .public-navbar {
            background: rgba(255, 255, 255, .8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--app-border);
        }

        .brand-mark {
            align-items: center;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #0ea5e9 100%);
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 1.1rem;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .12), 0 12px 30px rgba(79, 70, 229, .4);
            display: inline-flex;
            flex: 0 0 2.6rem;
            height: 2.6rem;
            justify-content: center;
            position: relative;
            width: 2.6rem;
        }
        .brand-mark::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background: radial-gradient(circle at 30% 25%, rgba(255, 255, 255, .5), transparent 45%);
            pointer-events: none;
        }
        .brand-mark i { position: relative; z-index: 1; text-shadow: 0 2px 6px rgba(15, 23, 42, .25); }

        .app-page { padding: 1.75rem 1rem; animation: fadeUp .35s ease both; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .card {
            border: 1px solid var(--app-border);
            border-radius: var(--app-radius);
            box-shadow: var(--app-shadow);
        }
        .card-header { background: var(--app-surface); border-bottom: 1px solid var(--app-border); border-radius: var(--app-radius) var(--app-radius) 0 0 !important; }

        .btn { border-radius: .75rem; font-weight: 600; }
        .btn-primary { background: var(--app-primary); border-color: var(--app-primary); }
        .btn-primary:hover, .btn-primary:focus { background: var(--app-primary-strong); border-color: var(--app-primary-strong); }
        .btn-outline-primary { color: var(--app-primary); border-color: var(--app-primary); }
        .btn-outline-primary:hover { background: var(--app-primary); border-color: var(--app-primary); }

        .form-control, .form-select { border-color: #cbd5e1; border-radius: .75rem; padding-top: .55rem; padding-bottom: .55rem; }
        .form-control:focus, .form-select:focus { border-color: #818cf8; box-shadow: 0 0 0 .25rem rgba(79, 70, 229, .12); }

        .badge { font-weight: 600; }

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

        .score-ring {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 3.4rem;
            height: 3.4rem;
            border-radius: 50%;
            background: conic-gradient(var(--c, #4f46e5) calc(var(--p, 0) * 1%), #e2e8f0 0);
            position: relative;
            flex: 0 0 auto;
        }
        .score-ring::before { content: ''; position: absolute; inset: 5px; border-radius: 50%; background: #fff; }
        .score-ring span { position: relative; z-index: 1; font-size: .68rem; font-weight: 700; color: var(--app-text); }

        .progress { border-radius: .6rem; background: #eef1f6; }
        .progress-bar { background: linear-gradient(90deg, #6366f1, #0ea5e9); border-radius: .6rem; }

        .hover-lift { transition: transform .18s ease, box-shadow .18s ease; }
        .hover-lift:hover { transform: translateY(-3px); box-shadow: var(--app-shadow-lg); }

        @media (min-width: 992px) {
            .app-page { padding: 2rem; }
        }
    </style>
</head>
<body>
<nav class="public-navbar sticky-top">
    <div class="container d-flex justify-content-between align-items-center py-2">
        <a class="text-decoration-none d-flex align-items-center gap-2 text-reset" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-question-lg fs-5 text-white"></i></span>
            <span class="fw-bold fs-5">IdeUsahaKu</span>
        </a>
        <div class="d-flex align-items-center gap-2">
            @auth
                @if (auth()->user()->is_admin)
                    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            @else
                <a class="btn btn-primary btn-sm" href="{{ route('login') }}">
                    <i class="bi bi-shield-lock me-1"></i>Login Admin
                </a>
            @endauth
        </div>
    </div>
</nav>

<main class="app-page">
    @if (session('status'))
        <div class="container mb-3">
            <div class="alert alert-success mb-0"><i class="bi bi-check-circle-fill me-1"></i>{{ session('status') }}</div>
        </div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
