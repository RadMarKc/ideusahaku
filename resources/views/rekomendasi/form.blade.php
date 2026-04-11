@extends('layouts.main')

@section('title', 'Sistem Rekomendasi Ide Usaha Mikro')

@section('content')
    <div class="container">
        <div class="p-4 p-md-5 rounded-3 app-hero mb-4">
            <h1 class="h3 mb-2">Perancangan dan Implementasi Sistem Rekomendasi Ide Usaha Mikro Berbasis Web</h1>
            <p class="mb-0">Metode: scoring berdasarkan parameter <strong>modal</strong>, <strong>lokasi</strong>, dan <strong>waktu luang</strong>.</p>
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

        <div class="row g-4">
            <div class="col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Input Parameter</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('rekomendasi.recommend') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Modal (Rupiah)</label>
                                <input type="number" name="capital" class="form-control" min="0" value="{{ $input['capital'] ?? '' }}" placeholder="Contoh: 500000">
                                <div class="form-text">Total modal awal yang tersedia.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lokasi</label>
                                <select name="location" class="form-select">
                                    <option value="">-- pilih lokasi --</option>
                                    @foreach($locations as $key => $label)
                                        <option value="{{ $key }}" @selected(($input['location'] ?? '') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Waktu Luang (jam/minggu)</label>
                                <input type="number" name="free_time_hours" class="form-control" min="0" max="168" value="{{ $input['free_time_hours'] ?? '' }}" placeholder="Contoh: 10">
                                <div class="form-text">Perkiraan total jam luang per minggu.</div>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Lihat Rekomendasi</button>
                        </form>
                    </div>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    <div class="small">
                        <div><strong>Catatan scoring</strong></div>
                        <div>Skor = 0.45×ModalFit + 0.35×WaktuFit + 0.20×LokasiFit</div>
                        <div>Hasil ditampilkan dalam persen (0–100).</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <strong>Hasil Rekomendasi</strong>
                        @if($recommendations)
                            <span class="text-muted small">
                                Modal: <strong>Rp{{ number_format($input['capital'] ?? 0, 0, ',', '.') }}</strong>,
                                Lokasi: <strong>{{ $locations[$input['location']] ?? '-' }}</strong>,
                                Waktu: <strong>{{ $input['free_time_hours'] ?? 0 }} jam</strong>
                            </span>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(!$recommendations)
                            <p class="text-muted mb-0">Isi parameter di sebelah kiri untuk mendapatkan rekomendasi.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                    <tr>
                                        <th>Ide Usaha</th>
                                        <th class="text-nowrap">Skor</th>
                                        <th class="text-nowrap">ModalFit</th>
                                        <th class="text-nowrap">WaktuFit</th>
                                        <th class="text-nowrap">LokasiFit</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($recommendations as $row)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">{{ $row['idea']->name }}</div>
                                                @if($row['idea']->description)
                                                    <div class="text-muted small">{{ $row['idea']->description }}</div>
                                                @endif
                                                <div class="text-muted small">
                                                    Modal: Rp{{ number_format($row['idea']->capital_min, 0, ',', '.') }}@if($row['idea']->capital_max)–Rp{{ number_format($row['idea']->capital_max, 0, ',', '.') }}@endif
                                                    • Waktu: {{ $row['idea']->free_time_min_hours }}@if($row['idea']->free_time_max_hours)–{{ $row['idea']->free_time_max_hours }}@endif jam/minggu
                                                </div>
                                            </td>
                                            <td class="text-nowrap">
                                                <span class="badge bg-success score-badge">{{ number_format($row['score'], 2) }}%</span>
                                            </td>
                                            <td class="text-nowrap">{{ number_format($row['breakdown']['capital_fit'], 2) }}%</td>
                                            <td class="text-nowrap">{{ number_format($row['breakdown']['time_fit'], 2) }}%</td>
                                            <td class="text-nowrap">{{ number_format($row['breakdown']['location_fit'], 2) }}%</td>
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

