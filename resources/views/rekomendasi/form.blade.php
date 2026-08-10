@extends('layouts.public')

@section('title', 'Sistem Rekomendasi Ide Usaha Mikro')

@section('content')
    <div class="container">
        <div class="app-hero rounded-4 p-4 p-md-5 mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="app-hero-icon">
                        <i class="bi bi-lightbulb fs-3"></i>
                    </div>
                    <div>
                        <p class="text-uppercase small mb-2 opacity-75">Weighted Product Recommendation</p>
                        <h1 class="h3 mb-2">Temukan ide usaha mikro yang paling cocok</h1>
                        <p class="mb-0 opacity-75">
                            Masukkan modal, pilihan kategori, dan waktu luang. Hasilnya dihitung dari kriteria aktif
                            di database dan relasi skor pada tiap ide usaha.
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill bg-white bg-opacity-25 border border-white border-opacity-25 px-3 py-2">
                        Modal: {{ number_format((float) $formula->modal_weight, 2) }}
                    </span>
                    <span class="badge rounded-pill bg-white bg-opacity-25 border border-white border-opacity-25 px-3 py-2">
                        Lokasi: {{ number_format((float) $formula->location_weight, 2) }}
                    </span>
                    <span class="badge rounded-pill bg-white bg-opacity-25 border border-white border-opacity-25 px-3 py-2">
                        Waktu: {{ number_format((float) $formula->time_weight, 2) }}
                    </span>
                </div>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-md-4">
                    <div class="stat-tile p-3 h-100">
                        <div class="small opacity-75 mb-1">Kriteria aktif</div>
                        <div class="h5 mb-0 fw-bold">{{ $criteria->count() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-tile p-3 h-100">
                        <div class="small opacity-75 mb-1">Data master kategori</div>
                        <div class="h5 mb-0 fw-bold">{{ $locations->count() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-tile p-3 h-100">
                        <div class="small opacity-75 mb-1">Data master waktu</div>
                        <div class="h5 mb-0 fw-bold">{{ $times->count() }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 align-items-start">
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header border-0 pt-4 px-4 pb-0 bg-transparent">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-sliders fs-5 text-primary"></i>
                            <strong>Parameter Rekomendasi</strong>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('rekomendasi.recommend') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="capital">
                                    <i class="bi bi-cash-coin me-1 text-primary"></i>Modal
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input
                                        id="capital"
                                        type="number"
                                        name="capital"
                                        class="form-control"
                                        min="0"
                                        value="{{ $input['capital'] ?? '' }}"
                                        placeholder="Contoh: 500000"
                                        required
                                        oninput="formatCapital(this)"
                                    >
                                </div>
                                <div class="form-text">Masukkan modal yang tersedia saat ini.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="location">
                                    <i class="bi bi-shop me-1 text-primary"></i>Kategori Usaha
                                </label>
                                <select id="location" name="location" class="form-select" required>
                                    <option value="">-- pilih kategori --</option>
                                    @foreach ($locations as $option)
                                        <option value="{{ $option->code }}" @selected(($input['location'] ?? '') === $option->code)>
                                            {{ $option->label }} ({{ $option->score }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Menggunakan master kategori usaha yang aktif di database.</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold" for="time">
                                    <i class="bi bi-clock-history me-1 text-primary"></i>Waktu Luang
                                </label>
                                <select id="time" name="time" class="form-select" required>
                                    <option value="">-- pilih waktu --</option>
                                    @foreach ($times as $option)
                                        <option value="{{ $option->code }}" @selected(($input['time'] ?? '') === $option->code)>
                                            {{ $option->label }} ({{ $option->score }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Pilih kategori waktu yang paling mendekati kondisi Anda.</div>
                            </div>

                            <button class="btn btn-primary w-100 py-2" type="submit">
                                <i class="bi bi-search me-1"></i>
                                Lihat Rekomendasi
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-calculator text-primary"></i>
                            <strong>Bagaimana perhitungannya?</strong>
                        </div>
                        <p class="text-muted small mb-2">
                            Skor tiap ide usaha dihitung menggunakan <strong>Weighted Product Method (WPM)</strong>:
                        </p>
                        <div class="text-center my-3">
                            <span class="badge rounded-pill text-bg-light border px-4 py-3 fw-normal">
                                Skor = Modal<sup>0.45</sup> &times; Lokasi<sup>0.30</sup> &times; Waktu<sup>0.25</sup>
                            </span>
                        </div>
                        <p class="text-muted small mb-0">
                            Bobot formula bisa diubah dari menu <strong>Master &rarr; Formula</strong> tanpa mengubah kode.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-award fs-5 text-primary"></i>
                            <strong>Hasil Rekomendasi</strong>
                        </div>
                        @if ($recommendations)
                            <span class="text-muted small">
                                Modal: <strong>Rp{{ number_format((int) ($input['capital'] ?? 0), 0, ',', '.') }}</strong>
                                @if ($selectedLocation || $selectedTime)
                                    | {{ $selectedLocation?->label ?? '-' }} | {{ $selectedTime?->label ?? '-' }}
                                @endif
                            </span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        @if (! $recommendations)
                            <div class="py-5 text-center text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3 text-primary opacity-50"></i>
                                <p class="mb-1">Isi parameter di sebelah kiri untuk menampilkan rekomendasi.</p>
                                <p class="small mb-0">Daftar hasil akan diurutkan berdasarkan skor Weighted Product.</p>
                            </div>
                        @else
                            <div class="d-grid gap-3">
                                @foreach ($recommendations as $row)
                                    @php
                                        $idea = $row['idea'];
                                        $scoresByCode = $idea->scores->mapWithKeys(
                                            fn ($score) => [$score->criterion?->code => (int) $score->score]
                                        );
                                        $locationSummary = implode(', ', $idea->suitable_locations ?? []);
                                        $scoreColor = match (true) {
                                            $row['score'] >= 70 => '#22c55e',
                                            $row['score'] >= 50 => '#f59e0b',
                                            default => '#ef4444',
                                        };
                                    @endphp
                                    <div class="card border-0 shadow-sm hover-lift overflow-hidden">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="score-ring" style="--c: {{ $scoreColor }}; --p: {{ min(100, $row['score']) }}; flex: 0 0 auto;">
                                                    <span>{{ number_format($row['score'], 2) }}%</span>
                                                </div>
                                                <div class="flex-grow-1 min-width-0">
                                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="badge bg-primary-subtle text-primary-emphasis">
                                                                #{{ $loop->iteration }}
                                                            </span>
                                                            <a class="fw-bold text-decoration-none text-reset" href="{{ route('rekomendasi.detail', $idea) }}">
                                                                {{ $idea->name }}
                                                                <i class="bi bi-arrow-up-right ms-1 small text-primary"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    @if ($idea->description)
                                                        <div class="text-muted small mt-1">{{ $idea->description }}</div>
                                                    @endif

                                                    <div class="d-flex flex-wrap gap-3 mt-3 small">
                                                        <span><i class="bi bi-cash-coin text-success me-1"></i>Rp{{ number_format($idea->capital_estimate, 0, ',', '.') }}</span>
                                                        <span><i class="bi bi-shop text-primary me-1"></i>{{ $idea->location_label ?: ($locationSummary !== '' ? $locationSummary : '-') }}</span>
                                                        <span><i class="bi bi-clock-history text-info me-1"></i>{{ $idea->time_label ?: '-' }}</span>
                                                    </div>

                                                    <div class="d-flex flex-wrap gap-2 mt-3">
                                                        @foreach ($criteria as $criterion)
                                                            <span class="badge rounded-pill text-bg-light border text-dark">
                                                                {{ $criterion->name }}: {{ (int) ($scoresByCode[$criterion->code] ?? 0) }}
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    <div class="mt-3">
                                                        @foreach ($criteria as $criterion)
                                                            @php
                                                                $criterionScore = (int) ($scoresByCode[$criterion->code] ?? 0);
                                                                $breakKey = match ($criterion->code) {
                                                                    'modal' => 'modal',
                                                                    'lokasi' => 'lokasi',
                                                                    'waktu' => 'waktu',
                                                                    default => null,
                                                                };
                                                                $breakValue = $breakKey !== null ? (float) ($row['breakdown'][$breakKey] ?? 0) : 0;
                                                            @endphp
                                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                                <span class="text-muted small" style="width: 80px;">{{ $criterion->name }}</span>
                                                                <div class="progress flex-grow-1" style="height: 8px;">
                                                                    <div class="progress-bar" style="width: {{ min(100, $breakValue) }}%"></div>
                                                                </div>
                                                                <span class="text-muted small text-nowrap" style="width: 48px; text-align: right;">
                                                                    {{ number_format($breakValue, 1) }}%
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function formatCapital(input) {
            const val = input.value.replace(/[^\d]/g, '');
            if (val === '') return;
            const formatted = Number(val).toLocaleString('id-ID');
            input.value = val;
            input.setAttribute('data-formatted', formatted);
        }
    </script>
@endpush
