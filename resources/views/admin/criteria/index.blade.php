@extends('layouts.main')

@section('title', 'Kriteria Formula')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-1"><i class="bi bi-sliders text-primary me-2"></i>Kriteria Formula</h1>
                <p class="text-muted mb-0">Atur bobot, tipe, urutan, dan status kriteria yang dipakai di rekomendasi.</p>
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

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-database-gear text-primary me-1"></i>
                    <strong>Daftar Kriteria</strong>
                </span>
                <span class="text-muted small">{{ $criteria->count() }} data</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Bobot</th>
                            <th>Tipe</th>
                            <th>Urut</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($criteria as $criterion)
                            <tr>
                                <form method="POST" action="{{ route('admin.criteria.update', $criterion) }}">
                                    @csrf
                                    @method('PUT')
                                    <td>
                                        <input class="form-control form-control-sm" name="code" value="{{ old('code', $criterion->code) }}" required>
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" name="name" value="{{ old('name', $criterion->name) }}" required>
                                    </td>
                                    <td style="min-width: 120px;">
                                        <input class="form-control form-control-sm" type="number" step="0.01" name="weight" value="{{ old('weight', $criterion->weight) }}" min="0" max="999.99" required>
                                    </td>
                                    <td style="min-width: 120px;">
                                        <select class="form-select form-select-sm" name="type" required>
                                            <option value="benefit" @selected(old('type', $criterion->type) === 'benefit')>Benefit</option>
                                            <option value="cost" @selected(old('type', $criterion->type) === 'cost')>Cost</option>
                                        </select>
                                    </td>
                                    <td style="min-width: 110px;">
                                        <input class="form-control form-control-sm" type="number" name="sort_order" value="{{ old('sort_order', $criterion->sort_order) }}" min="0" required>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $criterion->is_active))>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-primary" type="submit">
                                            <i class="bi bi-check2"></i>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mb-0">
                    Bobot di halaman rekomendasi akan otomatis mengikuti tabel ini karena controller membaca data langsung dari database.
                </div>
            </div>
        </div>
    </div>
@endsection
