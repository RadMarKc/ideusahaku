@extends('layouts.main')

@section('title', 'Login Pengguna')

@section('content')
    <div class="auth-wrap d-flex">
        <div class="auth-brand-panel d-none d-lg-flex flex-column justify-content-between p-5 w-50">
            <div class="d-flex align-items-center gap-3">
                <span class="app-hero-icon"><i class="bi bi-stars fs-3"></i></span>
                <div>
                    <div class="h5 mb-0 fw-bold">IdeUsahaKu</div>
                    <small class="opacity-75">Rekomendasi Usaha Mikro</small>
                </div>
            </div>
            <div>
                <h1 class="h2 fw-bold mb-3">Temukan ide usaha yang paling cocok untukmu.</h1>
                <p class="opacity-75 mb-4">
                    Sistem rekomendasi berbasis Weighted Product Method
                    yang menyesuaikan dengan modal, kategori, dan waktu luang Anda.
                </p>
                <div class="d-flex flex-column gap-2">
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-cash-coin fs-4"></i>
                        <div><div class="fw-semibold">Input Modal</div><small class="opacity-75">Sesuaikan dengan dana tersedia</small></div>
                    </div>
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-tags fs-4"></i>
                        <div><div class="fw-semibold">Pilih Kategori</div><small class="opacity-75">Online, Offline, Rumahan, Hybrid</small></div>
                    </div>
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-clock-history fs-4"></i>
                        <div><div class="fw-semibold">Waktu Luang</div><small class="opacity-75">Dari fleksibel hingga intensif</small></div>
                    </div>
                </div>
            </div>
            <small class="opacity-60">&copy; {{ date('Y') }} IdeUsahaKu &middot; Weighted Product Method</small>
        </div>

        <div class="flex-grow-1 d-flex align-items-center justify-content-center p-4">
            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <span class="brand-mark mx-auto mb-3"><i class="bi bi-stars fs-3 text-white"></i></span>
                    <h1 class="h3 fw-bold mb-1">Selamat Datang</h1>
                    <p class="text-muted mb-0">Masuk untuk mulai menggunakan aplikasi.</p>
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

                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-person me-1 text-primary"></i>Username
                                </label>
                                <input type="text" name="username" class="form-control" value="{{ old('username') }}" autocomplete="username" autofocus placeholder="Masukkan username">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-lock me-1 text-primary"></i>Password
                                </label>
                                <input type="password" name="password" class="form-control" autocomplete="current-password" placeholder="Masukkan password">
                            </div>

                            <button class="btn btn-primary w-100 py-2" type="submit">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Masuk
                            </button>
                        </form>
                    </div>
                </div>

                <p class="text-center text-muted small mt-3 mb-0">IdeUsahaKu &middot; Sistem Rekomendasi Usaha Mikro</p>
            </div>
        </div>
    </div>
@endsection
