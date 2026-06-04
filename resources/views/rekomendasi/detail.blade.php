@extends('layouts.main')

@section('title', $idea->name)

@section('content')
    <div class="container">
        <div class="mb-4">
            <a class="btn btn-outline-primary btn-sm" href="{{ route('rekomendasi.form') }}">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali ke Rekomendasi
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h1 class="h4 mb-0"><i class="bi bi-briefcase text-primary me-2"></i>{{ $idea->name }}</h1>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <h2 class="h6"><i class="bi bi-card-text text-primary me-1"></i>Deskripsi Lengkap</h2>
                        @if ($idea->description)
                            <p class="mb-0">{{ $idea->description }}</p>
                        @else
                            <p class="text-muted mb-0">Deskripsi lengkap usaha belum diisi oleh admin.</p>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <h2 class="h6"><i class="bi bi-info-circle text-primary me-1"></i>Informasi Usaha</h2>
                        <dl class="mb-0">
                            <dt><i class="bi bi-cash-coin me-1 text-primary"></i>Modal Minimal</dt>
                            <dd>Rp{{ number_format($idea->capital_min, 0, ',', '.') }}</dd>

                            <dt><i class="bi bi-wallet2 me-1 text-primary"></i>Modal Maksimal</dt>
                            <dd>{{ $idea->capital_max ? 'Rp' . number_format($idea->capital_max, 0, ',', '.') : '-' }}</dd>

                            <dt><i class="bi bi-clock-history me-1 text-primary"></i>Waktu Luang</dt>
                            <dd>
                                {{ $idea->free_time_min_hours }}@if($idea->free_time_max_hours)-{{ $idea->free_time_max_hours }}@endif jam/minggu
                            </dd>

                            <dt><i class="bi bi-shop me-1 text-primary"></i>Lokasi/Kategori</dt>
                            <dd>
                                @foreach (($idea->suitable_locations ?? []) as $location)
                                    <span class="badge bg-secondary me-1">{{ $locations[$location] ?? ucfirst($location) }}</span>
                                @endforeach
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
