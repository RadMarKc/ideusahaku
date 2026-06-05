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

    <div class="container py-4">
        <div class="mb-4">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('rekomendasi.form') }}">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Rekomendasi
            </a>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                            <div>
                                <p class="text-uppercase text-muted small mb-2">Detail Ide Usaha</p>
                                <h1 class="h3 mb-2">{{ $idea->name }}</h1>
                                <p class="text-muted mb-0">
                                    Data utama disimpan di tabel <code>micro_business_ideas</code>, sedangkan skor
                                    kriteria ada di relasi <code>scores</code>.
                                </p>
                            </div>
                            <div class="text-md-end">
                                <span class="badge {{ $idea->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                    {{ $idea->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                <div class="text-muted small mt-2">
                                    Total skor: <strong>{{ $idea->total_score }}</strong>
                                </div>
                            </div>
                        </div>

                        @if ($idea->description)
                            <div class="mb-4">
                                <h2 class="h6 mb-2">Deskripsi</h2>
                                <p class="mb-0">{{ $idea->description }}</p>
                            </div>
                        @endif

                        <div class="row g-3 mb-4">
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="text-muted small mb-1">Estimasi Modal</div>
                                    <div class="fw-semibold">Rp{{ number_format($idea->capital_estimate, 0, ',', '.') }}</div>
                                    <div class="text-muted small">Minimal: Rp{{ number_format($idea->capital_min, 0, ',', '.') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="text-muted small mb-1">Waktu Luang</div>
                                    <div class="fw-semibold">
                                        {{ $idea->free_time_min_hours }}@if($idea->free_time_max_hours)-{{ $idea->free_time_max_hours }}@endif jam/minggu
                                    </div>
                                    <div class="text-muted small">Disimpan pada kolom free_time_*. </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="text-muted small mb-1">Kategori/Lokasi</div>
                                    <div class="fw-semibold">
                                        {{ $idea->location_label ?: ($locationSummary !== '' ? $locationSummary : '-') }}
                                    </div>
                                    <div class="text-muted small">Mengacu ke master option aktif.</div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="p-3 rounded-3 bg-light border h-100">
                                    <div class="text-muted small mb-1">Skor Akumulasi</div>
                                    <div class="fw-semibold">{{ $idea->total_score }}</div>
                                    <div class="text-muted small">Modal + lokasi + waktu.</div>
                                </div>
                            </div>
                        </div>

                        <h2 class="h6 mb-3">Nilai Kriteria</h2>
                        <div class="row g-3">
                            @foreach ($criteria as $criterion)
                                @php
                                    $criterionScore = (int) ($scoresByCode[$criterion->code] ?? 0);
                                @endphp
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 border h-100">
                                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                            <div>
                                                <div class="fw-semibold">{{ $criterion->name }}</div>
                                                <div class="text-muted small">{{ $criterion->code }}</div>
                                            </div>
                                            <span class="badge {{ $criterion->type === 'cost' ? 'bg-warning text-dark' : 'bg-primary' }}">
                                                {{ $criterion->type }}
                                            </span>
                                        </div>
                                        <div class="display-6 fw-semibold mb-2" style="line-height: 1;">
                                            {{ $criterionScore }}
                                        </div>
                                        <div class="text-muted small">
                                            Bobot: {{ number_format((float) $criterion->weight, 2) }}
                                            @if ($criterion->is_active)
                                                | aktif
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
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Ringkasan Data</h2>
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
                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                        {{ $locations->get($location)?->label ?? ucfirst($location) }}
                                    </span>
                                @empty
                                    <span class="text-muted">-</span>
                                @endforelse
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Catatan Struktur</h2>
                        <p class="text-muted small mb-0">
                            Halaman ini membaca data utama dari <code>micro_business_ideas</code>, lalu skor tiap kriteria
                            dari tabel <code>micro_business_idea_scores</code> yang terhubung ke <code>criteria</code>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
