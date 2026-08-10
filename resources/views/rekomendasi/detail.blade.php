@extends('layouts.main')

@section('title', $idea->name)

@section('content')
    @php
        $scoresByCode = $idea->scores->mapWithKeys(
            fn ($score) => [$score->criterion?->code => (int) $score->score]
        );
        $locationSummary = collect($idea->suitable_locations ?? [])
            ->map(fn ($location) => $locations->get($location)?->label ?? ucfirst($location))
            ->filter()
            ->implode(', ');
    @endphp

    <div class="container">
        <div class="mb-4">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('rekomendasi.form') }}">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Rekomendasi
            </a>
        </div>

        <div class="app-hero rounded-4 p-4 p-md-5 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-end">
                <div>
                    <p class="text-uppercase small mb-2 opacity-75">Detail Ide Usaha</p>
                    <h1 class="h2 fw-bold mb-2">{{ $idea->name }}</h1>
                    <p class="mb-0 opacity-75">{{ $idea->description }}</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill bg-white bg-opacity-25 border border-white border-opacity-25 px-3 py-2">
                        Total skor: {{ $idea->total_score }}
                    </span>
                    <span class="badge rounded-pill {{ $idea->is_active ? 'bg-white text-success' : 'bg-white text-secondary' }} px-3 py-2">
                        <i class="bi bi-{{ $idea->is_active ? 'check-circle' : 'x-circle' }} me-1"></i>
                        {{ $idea->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle h-100">
                                    <div class="text-muted small mb-1"><i class="bi bi-cash-coin me-1"></i>Estimasi Modal</div>
                                    <div class="fw-bold fs-5">Rp{{ number_format($idea->capital_estimate, 0, ',', '.') }}</div>
                                    <div class="text-muted small">Minimal: Rp{{ number_format($idea->capital_min, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-info-subtle border border-info-subtle h-100">
                                    <div class="text-muted small mb-1"><i class="bi bi-clock-history me-1"></i>Waktu Luang</div>
                                    <div class="fw-bold fs-5">
                                        {{ $idea->free_time_min_hours }}@if($idea->free_time_max_hours)-{{ $idea->free_time_max_hours }}@endif <small class="fs-6">jam/mgg</small>
                                    </div>
                                    <div class="text-muted small">{{ $idea->time_label ?: '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle h-100">
                                    <div class="text-muted small mb-1"><i class="bi bi-shop me-1"></i>Kategori</div>
                                    <div class="fw-bold fs-5 text-truncate">
                                        {{ $idea->location_label ?: ($locationSummary !== '' ? $locationSummary : '-') }}
                                    </div>
                                    <div class="text-muted small">Lokasi sesuai</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-warning-subtle border border-warning-subtle h-100">
                                    <div class="text-muted small mb-1"><i class="bi bi-stars me-1"></i>Skor Akumulasi</div>
                                    <div class="fw-bold fs-5">{{ $idea->total_score }}</div>
                                    <div class="text-muted small">Modal + lokasi + waktu</div>
                                </div>
                            </div>
                        </div>

                        <h2 class="h6 fw-bold mb-3">Nilai Kriteria</h2>
                        <div class="row g-3">
                            @foreach ($criteria as $criterion)
                                @php
                                    $criterionScore = (int) ($scoresByCode[$criterion->code] ?? 0);
                                    $tone = $criterion->type === 'cost'
                                        ? 'bg-warning-subtle border-warning-subtle'
                                        : 'bg-primary-subtle border-primary-subtle';
                                @endphp
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 border {{ $tone }} h-100">
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">{{ $criterion->name }}</div>
                                                <div class="text-muted small">{{ $criterion->code }}</div>
                                            </div>
                                            <span class="badge {{ $criterion->type === 'cost' ? 'text-bg-warning' : 'text-bg-primary' }}">
                                                {{ $criterion->type }}
                                            </span>
                                        </div>
                                        <div class="display-6 fw-bold mb-2" style="line-height: 1;">
                                            {{ $criterionScore }}
                                        </div>
                                        <div class="text-muted small">
                                            Bobot: {{ number_format((float) $criterion->weight, 2) }}
                                            @if ($criterion->is_active)
                                                <span class="badge text-bg-success ms-1">aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h6 fw-bold mb-3"><i class="bi bi-card-list me-1 text-primary"></i>Ringkasan Data</h2>
                        <dl class="mb-0">
                            <dt class="text-muted small">Modal Maksimal</dt>
                            <dd class="mb-3">{{ $idea->capital_max ? 'Rp' . number_format($idea->capital_max, 0, ',', '.') : '-' }}</dd>

                            <dt class="text-muted small">Waktu Luang</dt>
                            <dd class="mb-3">
                                {{ $idea->free_time_min_hours }}@if($idea->free_time_max_hours)-{{ $idea->free_time_max_hours }}@endif jam/minggu
                            </dd>

                            <dt class="text-muted small">Lokasi yang Sesuai</dt>
                            <dd class="mb-0">
                                @forelse (($idea->suitable_locations ?? []) as $location)
                                    <span class="badge rounded-pill bg-light text-dark border me-1 mb-1">
                                        {{ $locations->get($location)?->label ?? ucfirst($location) }}
                                    </span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h2 class="h6 fw-bold mb-3"><i class="bi bi-lightbulb me-1 text-primary"></i>Tips</h2>
                        <p class="text-muted small mb-0">
                            Mulailah dengan modal yang sesuai, pilih kategori yang dekat dengan tempat tinggal,
                            dan sesuaikan usaha dengan waktu luang yang Anda miliki.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
