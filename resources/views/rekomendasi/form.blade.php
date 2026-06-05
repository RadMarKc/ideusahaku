@extends('layouts.main')

@section('title', 'Sistem Rekomendasi Ide Usaha Mikro')

@section('content')
    <div class="container py-4">
        <div class="p-4 p-md-5 rounded-4 app-hero mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="app-hero-icon">
                        <i class="bi bi-lightbulb fs-3"></i>
                    </div>
                    <div>
                        <p class="text-uppercase text-muted small mb-2">Weighted Product Recommendation</p>
                        <h1 class="h3 mb-2">Temukan ide usaha mikro yang paling cocok</h1>
                        <p class="mb-0 text-body-secondary">
                            Masukkan modal, pilihan kategori, dan waktu luang. Hasilnya dihitung dari kriteria aktif
                            di database dan relasi skor pada tiap ide usaha.
                        </p>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @foreach ($criteria as $criterion)
                        <span class="badge rounded-pill text-bg-light border px-3 py-2">
                            {{ $criterion->name }}: {{ number_format((float) $criterion->weight, 2) }}
                        </span>
                    @endforeach
                </div>
            </div>

            <div class="row g-3 mt-4">
                <div class="col-md-4">
                    <div class="p-3 rounded-3 bg-white bg-opacity-75 border h-100">
                        <div class="text-muted small mb-1">Kriteria aktif</div>
                        <div class="h5 mb-0">{{ $criteria->count() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 bg-white bg-opacity-75 border h-100">
                        <div class="text-muted small mb-1">Data master lokasi</div>
                        <div class="h5 mb-0">{{ $locations->count() }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded-3 bg-white bg-opacity-75 border h-100">
                        <div class="text-muted small mb-1">Data master waktu</div>
                        <div class="h5 mb-0">{{ $times->count() }}</div>
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
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-sliders text-primary"></i>
                            <strong>Parameter Rekomendasi</strong>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('rekomendasi.recommend') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label" for="capital">
                                    <i class="bi bi-cash-coin me-1 text-primary"></i>Modal
                                </label>
                                <input
                                    id="capital"
                                    type="number"
                                    name="capital"
                                    class="form-control"
                                    min="0"
                                    value="{{ $input['capital'] ?? '' }}"
                                    placeholder="Contoh: 500000"
                                    required
                                >
                                <div class="form-text">Masukkan modal yang tersedia saat ini.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="location">
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
                                <label class="form-label" for="time">
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

                            <button class="btn btn-primary w-100" type="submit">
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
                            <strong>Catatan Perhitungan</strong>
                        </div>
                        <p class="text-muted small mb-2">
                            Nilai tiap ide usaha dihitung dari skor <code>modal</code>, <code>lokasi</code>, dan
                            <code>waktu</code> yang tersimpan pada tabel <code>micro_business_idea_scores</code>.
                        </p>
                        <p class="text-muted small mb-0">
                            Bobot mengikuti tabel <code>criteria</code>, jadi jika admin mengubah bobot di database,
                            hasil rekomendasi ikut menyesuaikan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex flex-column flex-md-row justify-content-between gap-2 align-items-md-center">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-award text-primary"></i>
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
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <p class="mb-1">Isi parameter di sebelah kiri untuk menampilkan rekomendasi.</p>
                                <p class="small mb-0">Daftar hasil akan diurutkan berdasarkan skor Weighted Product.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle table-hover">
                                    <thead class="table-light">
                                    <tr>
                                        <th class="text-nowrap">#</th>
                                        <th>Usaha</th>
                                        <th class="text-nowrap">Data Utama</th>
                                        <th class="text-nowrap">Skor Kriteria</th>
                                        <th class="text-nowrap">Nilai WPP</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($recommendations as $row)
                                        @php
                                            $idea = $row['idea'];
                                            $scoresByCode = $idea->scores->mapWithKeys(
                                                fn ($score) => [$score->criterion?->code => (int) $score->score]
                                            );
                                            $locationSummary = implode(', ', $idea->suitable_locations ?? []);
                                        @endphp
                                        <tr>
                                            <td class="text-muted fw-semibold">{{ $loop->iteration }}</td>
                                            <td>
                                                <a class="fw-semibold text-decoration-none" href="{{ route('rekomendasi.detail', $idea) }}">
                                                    <i class="bi bi-arrow-up-right-circle me-1"></i>{{ $idea->name }}
                                                </a>
                                                @if ($idea->description)
                                                    <div class="text-muted small mt-1">{{ $idea->description }}</div>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <div class="small">
                                                    <div>Modal: Rp{{ number_format($idea->capital_estimate, 0, ',', '.') }}</div>
                                                    <div>Minimal: Rp{{ number_format($idea->capital_min, 0, ',', '.') }}</div>
                                                    <div>Kategori: {{ $idea->location_label ?: ($locationSummary !== '' ? $locationSummary : '-') }}</div>
                                                    <div>Waktu: {{ $idea->time_label ?: '-' }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach ($criteria as $criterion)
                                                        @php
                                                            $criterionScore = (int) ($scoresByCode[$criterion->code] ?? 0);
                                                        @endphp
                                                        <span class="badge rounded-pill text-bg-light border text-dark">
                                                            {{ $criterion->name }}: {{ $criterionScore }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                                <div class="text-muted small mt-2">
                                                    Modal {{ number_format((float) ($row['breakdown']['modal'] ?? 0), 2) }}% |
                                                    Lokasi {{ number_format((float) ($row['breakdown']['lokasi'] ?? 0), 2) }}% |
                                                    Waktu {{ number_format((float) ($row['breakdown']['waktu'] ?? 0), 2) }}%
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success score-badge">
                                                    {{ number_format($row['score'], 2) }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
