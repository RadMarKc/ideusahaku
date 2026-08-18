@extends('layouts.main')

@section('title', 'Reset Password')

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
                <h1 class="h2 fw-bold mb-3">Buat password baru Anda.</h1>
                <p class="opacity-75 mb-4">
                    Gunakan kombinasi huruf, angka, dan simbol agar akun admin
                    semakin aman.
                </p>
                <div class="d-flex flex-column gap-2">
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-123 fs-4"></i>
                        <div><div class="fw-semibold">Minimal 8 Karakter</div><small class="opacity-75">Kombinasi huruf dan angka</small></div>
                    </div>
                    <div class="stat-tile p-3 d-flex align-items-center gap-3">
                        <i class="bi bi-eye fs-4"></i>
                        <div><div class="fw-semibold">Pratinjau</div><small class="opacity-75">Lihat password saat mengetik</small></div>
                    </div>
                </div>
            </div>
            <small class="opacity-60">&copy; {{ date('Y') }} IdeUsahaKu &middot; Weighted Product Method</small>
        </div>

        <div class="flex-grow-1 d-flex align-items-center justify-content-center p-4">
            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-4">
                    <a class="brand-mark mx-auto mb-3 d-inline-flex" href="{{ route('login') }}">
                        <i class="bi bi-shield-lock fs-3 text-white"></i>
                    </a>
                    <h1 class="h3 fw-bold mb-1">Reset Password</h1>
                    <p class="text-muted mb-0">Masukkan email dan password baru Anda.</p>
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

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-envelope me-1 text-primary"></i>Email Terdaftar
                                </label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" autocomplete="email" required placeholder="contoh@email.com">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-lock me-1 text-primary"></i>Password Baru
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control" autocomplete="new-password" minlength="8" required placeholder="Minimal 8 karakter">
                                    <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password" aria-label="Lihat kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Password minimal 8 karakter.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-lock-fill me-1 text-primary"></i>Konfirmasi Password
                                </label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password" minlength="8" required placeholder="Ulangi password baru">
                                    <button class="btn btn-outline-secondary password-toggle" type="button" data-target="password_confirmation" aria-label="Lihat kata sandi">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <button class="btn btn-primary w-100 py-2" type="submit">
                                <i class="bi bi-check2-circle me-2"></i>
                                Reset Password
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

@push('scripts')
    <script>
        document.querySelectorAll('.password-toggle').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById(btn.dataset.target);
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                btn.innerHTML = show
                    ? '<i class="bi bi-eye-slash"></i>'
                    : '<i class="bi bi-eye"></i>';
                btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Lihat kata sandi');
            });
        });
    </script>
@endpush