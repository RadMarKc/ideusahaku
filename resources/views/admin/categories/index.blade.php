@extends('layouts.main')

@php
    use App\Models\BusinessMasterOption;

    $section = $section ?? 'all';
    $pageTitle = match ($section) {
        'modal' => 'Modal Usaha',
        'kategori' => 'Kategori Usaha',
        'waktu' => 'Waktu Luang',
        default => 'Master Data Usaha',
    };
@endphp

@section('title', $pageTitle)

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-bold"><i class="bi bi-folder2-open text-primary me-2"></i>{{ $pageTitle }}</h1>
                <p class="text-muted mb-0">Kelola skor master yang dipakai oleh import data dan rekomendasi.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('admin.business-ideas.index') }}">
                <i class="bi bi-database-check me-1"></i>
                Buka Data Usaha
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success"><i class="bi bi-check-circle-fill me-1"></i>{{ session('status') }}</div>
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

        @if (in_array($section, ['all', 'modal'], true))
            <div class="card shadow-sm mb-4" id="modal-usaha">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-cash-coin text-primary me-1"></i><strong>Modal Usaha</strong></span>
                    <span class="text-muted small">{{ $capitals->count() }} rentang</span>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.master-options.store') }}" class="row g-2 align-items-end mb-3">
                        @csrf
                        <input type="hidden" name="type" value="{{ BusinessMasterOption::TYPE_CAPITAL }}">
                        <div class="col-md-3">
                            <label class="form-label">Rentang Modal</label>
                            <input class="form-control" name="label" placeholder="Rp0 - Rp500.000" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Kode</label>
                            <input class="form-control" name="code" placeholder="modal_0_500k" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Min</label>
                            <input class="form-control" type="number" name="value_min" min="0" placeholder="0">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Max</label>
                            <input class="form-control" type="number" name="value_max" min="0" placeholder="500000">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Skor</label>
                            <input class="form-control" type="number" name="score" min="0" max="255" value="1" required>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Urut</label>
                            <input class="form-control" type="number" name="sort_order" min="0" value="{{ $capitals->count() + 1 }}" required>
                        </div>
                        <div class="col-md-1">
                            <input type="hidden" name="is_active" value="1">
                            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-plus-lg"></i></button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                            <tr>
                                <th>Rentang Modal</th>
                                <th>Kode</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Skor</th>
                                <th>Urut</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($capitals as $option)
                                <tr>
                                    <form method="POST" action="{{ route('admin.master-options.update', $option) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="type" value="{{ $option->type }}">
                                        <td><input class="form-control form-control-sm" name="label" value="{{ $option->label }}" required></td>
                                        <td><input class="form-control form-control-sm" name="code" value="{{ $option->code }}" required></td>
                                        <td><input class="form-control form-control-sm" type="number" name="value_min" value="{{ $option->value_min }}" min="0"></td>
                                        <td><input class="form-control form-control-sm" type="number" name="value_max" value="{{ $option->value_max }}" min="0"></td>
                                        <td><input class="form-control form-control-sm" type="number" name="score" value="{{ $option->score }}" min="0" max="255" required></td>
                                        <td><input class="form-control form-control-sm" type="number" name="sort_order" value="{{ $option->sort_order }}" min="0" required></td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked($option->is_active)>
                                            </div>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            <button class="btn btn-sm btn-primary" type="submit"><i class="bi bi-check2"></i></button>
                                    </form>
                                            <form method="POST" action="{{ route('admin.master-options.destroy', $option) }}" class="d-inline" onsubmit="return confirm('Hapus master {{ $option->label }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="text-muted">Belum ada master modal.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if (in_array($section, ['all', 'kategori'], true))
            <div class="card shadow-sm mb-4" id="kategori-usaha">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tags text-primary me-1"></i><strong>Kategori Usaha</strong></span>
                    <span class="text-muted small">{{ $locations->count() }} kategori</span>
                </div>
                <div class="card-body">
                    @include('admin.categories.partials.option-table', [
                        'options' => $locations,
                        'type' => BusinessMasterOption::TYPE_LOCATION,
                        'labelTitle' => 'Kategori',
                        'labelPlaceholder' => 'Online',
                        'codePlaceholder' => 'online',
                    ])
                </div>
            </div>
        @endif

        @if (in_array($section, ['all', 'waktu'], true))
            <div class="card shadow-sm" id="waktu-luang">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock text-primary me-1"></i><strong>Waktu Luang</strong></span>
                    <span class="text-muted small">{{ $times->count() }} opsi</span>
                </div>
                <div class="card-body">
                    @include('admin.categories.partials.option-table', [
                        'options' => $times,
                        'type' => BusinessMasterOption::TYPE_TIME,
                        'labelTitle' => 'Waktu',
                        'labelPlaceholder' => 'Fleksibel',
                        'codePlaceholder' => 'fleksibel',
                    ])
                </div>
            </div>
        @endif
    </div>
@endsection
