@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <div class="app-hero rounded-4 p-4 p-md-5 mb-4">
            <div class="d-flex align-items-start gap-3">
                <div class="app-hero-icon">
                    <i class="bi bi-question-lg fs-3"></i>
                </div>
                <div>
                    <p class="text-uppercase small mb-2 opacity-75">Ringkasan Sistem</p>
                    <h1 class="h3 mb-2">Selamat datang, {{ auth()->user()->name }}</h1>
                    <p class="mb-0 opacity-75">
                        Kelola data master, formula, dan ide usaha, lalu dapatkan rekomendasi
                        berbasis Weighted Product Method.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('rekomendasi.form') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small">Ide Usaha Aktif</span>
                                <span class="badge text-bg-primary"><i class="bi bi-briefcase"></i></span>
                            </div>
                            <div class="h3 mb-0 fw-bold">{{ $ideaCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.master.categories.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small">Kategori Usaha</span>
                                <span class="badge text-bg-info"><i class="bi bi-tags"></i></span>
                            </div>
                            <div class="h3 mb-0 fw-bold">{{ $locationCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.master.times.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small">Waktu Luang</span>
                                <span class="badge text-bg-warning"><i class="bi bi-clock"></i></span>
                            </div>
                            <div class="h3 mb-0 fw-bold">{{ $timeCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.master.formula.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm hover-lift h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="text-muted small">Kriteria Aktif</span>
                                <span class="badge text-bg-success"><i class="bi bi-sliders"></i></span>
                            </div>
                            <div class="h3 mb-0 fw-bold">{{ $criteriaCount }}</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <strong><i class="bi bi-award text-primary me-1"></i>Skor Kriteria Aktif</strong>
                    </div>
                    <div class="card-body">
                        @php
                            $weightTotal = (float) $formula->modal_weight + (float) $formula->location_weight + (float) $formula->time_weight;
                        @endphp
                        @foreach ($criteria as $criterion)
                            @php
                                $weight = (float) $criterion->weight;
                                $pct = $weightTotal > 0 ? ($weight / $weightTotal) * 100 : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-semibold">{{ $criterion->name }}</span>
                                    <span class="text-muted small">{{ number_format($weight, 2) }}</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: {{ $pct }}%"></div>
                                </div>
                                <div class="text-muted small mt-1">{{ $criterion->code }} &middot; {{ $criterion->type }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white">
                        <strong><i class="bi bi-question-circle text-primary me-1"></i>Langkah Cepat</strong>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-3">
                            <a class="d-flex align-items-center gap-3 text-decoration-none text-reset p-3 rounded-3 bg-primary-subtle border border-primary-subtle hover-lift" href="{{ route('rekomendasi.form') }}">
                                <span class="fs-4"><i class="bi bi-graph-up-arrow text-primary"></i></span>
                                <div>
                                    <div class="fw-bold">Dapatkan Rekomendasi</div>
                                    <div class="text-muted small">Input modal, kategori &amp; waktu untuk hasil terbaik.</div>
                                </div>
                            </a>
                            <a class="d-flex align-items-center gap-3 text-decoration-none text-reset p-3 rounded-3 bg-info-subtle border border-info-subtle hover-lift" href="{{ route('admin.business-ideas.index') }}">
                                <span class="fs-4"><i class="bi bi-database-check text-info"></i></span>
                                <div>
                                    <div class="fw-bold">Kelola Data Usaha</div>
                                    <div class="text-muted small">Import &amp; perbarui dataset ide usaha.</div>
                                </div>
                            </a>
                            <a class="d-flex align-items-center gap-3 text-decoration-none text-reset p-3 rounded-3 bg-warning-subtle border border-warning-subtle hover-lift" href="{{ route('admin.master.formula.index') }}">
                                <span class="fs-4"><i class="bi bi-sliders text-warning"></i></span>
                                <div>
                                    <div class="fw-bold">Atur Formula</div>
                                    <div class="text-muted small">Sesuaikan bobot modal, lokasi &amp; waktu.</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
