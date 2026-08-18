@extends('layouts.main')

@section('title', 'Lupa Password')

@section('content')
    <div class="auth-wrap d-flex">
        <div class="auth-brand-panel d-none d-lg-flex flex-column justify-content-between p-5 w-50">
            <div class="d-flex align-items-center gap-3">
                <span class="app-hero-icon"><i class="bi bi-question-lg fs-3"></i></span>
                <div>
                    <div class="h5 mb-0 fw-bold">IdeUsahaKu</div>
                    <small class="opacity-75">Rekomendasi Usaha Mikro</small>
                </div>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-3">Tenang, semua bisa diatur ulang.</h1>
                <p class="opacity-75 mb-4">
                    Masukkan email yang terdaftar untuk mendapatkan tautan
                    reset password akun admin Anda.
                </p>
                <div class="d-flex flex-column gap-2">
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-envelope-check fs-4"></i>
                        <div><div class="fw-semibold">Cek Email</div><small class="opacity-75">Tautan reset dikirimkan ke email Anda</small></div>
                    </div>
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-shield-lock fs-4"></i>
                        <div><div class="fw-semibold">Aman</div><small class="opacity-75">Tautan hanya berlaku sementara</small></div>
                    </div>
                </div>
            </div>
            <small class="opacity-60">&copy; {{ date('Y') }} IdeUsahaKu &middot; Weighted Product Method</small>
        </div>

        <div class="flex-grow-1 d-flex align-items-center justify-content-center p-4">
            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <a class="brand-mark mx-auto mb-3 d-inline-flex" href="{{ route('login') }}">
                        <i class="bi bi-key fs-3 text-white"></i>
                    </a>
                    <h1 class="h3 fw-bold mb-1">Lupa Password</h1>
                    <p class="text-muted mb-0">Masukkan email terdaftar untuk mereset password.</p>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-1"></i>{{ session('status') }}
                            </div>
                        @endif

                        @if ($link = session('reset_link'))
                            <div class="alert alert-info">
                                <div class="fw-semibold mb-2">
                                    <i class="bi bi-link-45deg me-1"></i>Tautan reset password Anda:
                                </div>
                                <div class="input-group input-group-sm mb-2">
                                    <input type="text" class="form-control form-control-sm" id="resetLink" value="{{ $link }}" readonly style="font-size: .72rem;">
                                    <button class="btn btn-outline-primary btn-sm" type="button" id="copyResetLink">
                                        <i class="bi bi-clipboard"></i> Salin
                                    </button>
                                </div>
                                <small class="d-block">Tautan berlaku 60 menit dan hanya bisa dipakai sekali.</small>
                            </div>
                        @endif

                        @push('scripts')
                            <script>
                                const copyBtn = document.getElementById('copyResetLink');
                                if (copyBtn) {
                                    copyBtn.addEventListener('click', function () {
                                        const input = document.getElementById('resetLink');
                                        input.focus();
                                        input.select();
                                        navigator.clipboard && navigator.clipboard.writeText(input.value);
                                        copyBtn.innerHTML = '<i class="bi bi-check2"></i> Tersalin';
                                        setTimeout(() => {
                                            copyBtn.innerHTML = '<i class="bi bi-clipboard"></i> Salin';
                                        }, 2000);
                                    });
                                }
                            </script>
                        @endpush

                        @if (! $link)
                            <div class="alert alert-warning small">
                                <i class="bi bi-info-circle me-1"></i>
                                Pada mode demo (tanpa layanan email), tautan reset akan langsung
                                ditampilkan di halaman ini.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-envelope me-1 text-primary"></i>Email Terdaftar
                                </label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', session('reset_email')) }}" autocomplete="email" autofocus placeholder="contoh@email.com">
                            </div>

                            <button class="btn btn-primary w-100 py-2" type="submit">
                                <i class="bi bi-send me-2"></i>
                                Kirim Tautan Reset
                            </button>
                        </form>

                        <hr class="my-4">

                        <div class="text-center small text-muted">
                            Ingat password? <a class="text-decoration-none" href="{{ route('login') }}">Kembali ke login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection