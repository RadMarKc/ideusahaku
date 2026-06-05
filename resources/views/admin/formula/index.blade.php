@extends('layouts.main')

@section('title', 'Formula Rekomendasi')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-1"><i class="bi bi-sliders text-primary me-2"></i>Formula Rekomendasi</h1>
                <p class="text-muted mb-0">Bobot ini dipakai langsung oleh perhitungan Weighted Product pada halaman rekomendasi.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('rekomendasi.form') }}">
                <i class="bi bi-graph-up-arrow me-1"></i>
                Lihat Rekomendasi
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $weightTotal = (float) $formula->modal_weight + (float) $formula->location_weight + (float) $formula->time_weight;
        @endphp

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Ubah Bobot Formula</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.master.formula.update', $formula) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="modal_weight">Modal</label>
                                    <input
                                        id="modal_weight"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="9.99"
                                        name="modal_weight"
                                        class="form-control"
                                        value="{{ old('modal_weight', $formula->modal_weight) }}"
                                        required
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="location_weight">Lokasi</label>
                                    <input
                                        id="location_weight"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="9.99"
                                        name="location_weight"
                                        class="form-control"
                                        value="{{ old('location_weight', $formula->location_weight) }}"
                                        required
                                    >
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="time_weight">Waktu</label>
                                    <input
                                        id="time_weight"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        max="9.99"
                                        name="time_weight"
                                        class="form-control"
                                        value="{{ old('time_weight', $formula->time_weight) }}"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center gap-3 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $formula->is_active))>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-check2-circle me-1"></i>
                                    Simpan Formula
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <strong>Ringkasan</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-muted small">Modal</div>
                            <div class="fw-semibold">{{ number_format((float) $formula->modal_weight, 2) }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Lokasi</div>
                            <div class="fw-semibold">{{ number_format((float) $formula->location_weight, 2) }}</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-muted small">Waktu</div>
                            <div class="fw-semibold">{{ number_format((float) $formula->time_weight, 2) }}</div>
                        </div>
                        <div class="alert alert-info mb-0">
                            Total bobot saat ini: <strong>{{ number_format($weightTotal, 2) }}</strong>
                            <div class="small mt-1">Jika total tidak sama dengan 1.00, controller akan menormalkan otomatis saat menghitung rekomendasi.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
