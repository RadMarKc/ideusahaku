@extends('layouts.main')

@section('title', 'Dashboard Data Usaha')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-1"><i class="bi bi-database-check text-primary me-2"></i>Data Usaha</h1>
                <p class="text-muted mb-0">Upload data dari CSV/Excel, lalu lengkapi deskripsi usaha langsung dari dashboard.</p>
            </div>
            <a class="btn btn-primary" href="{{ route('rekomendasi.form') }}">
                <i class="bi bi-graph-up-arrow me-1"></i>
                Buka Rekomendasi
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

        <div class="row g-4 mb-4">
            <div class="col-xl-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white">
                        <i class="bi bi-cloud-arrow-up text-primary me-1"></i>
                        <strong>Upload CSV/Excel</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.business-ideas.import') }}" enctype="multipart/form-data" class="row g-3 align-items-end">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label" for="business_file">File Data Usaha</label>
                                <input class="form-control" id="business_file" type="file" name="business_file" accept=".csv,.txt,.xlsx" required>
                                <div class="form-text">Gunakan file CSV, TXT, atau Excel .xlsx dengan format kolom di samping.</div>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="bi bi-upload me-1"></i>
                                    Import Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2">
                        <span>
                            <i class="bi bi-file-earmark-spreadsheet text-primary me-1"></i>
                            <strong>Template Import</strong>
                        </span>
                        <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.business-ideas.template') }}">
                            <i class="bi bi-download me-1"></i>
                            Download CSV
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm align-middle mb-3">
                                <thead>
                                <tr>
                                    <th>Kolom</th>
                                    <th>Contoh</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><code>namausaha</code></td>
                                    <td>Laundry Kiloan</td>
                                </tr>
                                <tr>
                                    <td><code>modal</code></td>
                                    <td>1000000</td>
                                </tr>
                                <tr>
                                    <td><code>skormodal</code></td>
                                    <td>3</td>
                                </tr>
                                <tr>
                                    <td><code>kategori</code></td>
                                    <td>online/offline/rumahan/hybrid</td>
                                </tr>
                                <tr>
                                    <td><code>waktu</code></td>
                                    <td>rendah/sedang/tinggi</td>
                                </tr>
                                <tr>
                                    <td><code>deskripsi</code></td>
                                    <td>Deskripsi lengkap usaha</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mb-0">Kolom alternatif yang juga diterima: <code>nama_usaha</code>, <code>name</code>, <code>lokasi</code>, dan <code>description</code>.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span>
                    <i class="bi bi-list-check text-primary me-1"></i>
                    <strong>Daftar Usaha</strong>
                </span>
                <span class="text-muted small">{{ $ideas->total() }} data</span>
            </div>
            <div class="card-body">
                @if ($ideas->isEmpty())
                    <p class="text-muted mb-0">Belum ada data usaha. Upload file CSV/Excel untuk mulai mengisi data.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                            <tr>
                                <th>Usaha</th>
                                <th class="text-nowrap">Parameter</th>
                                <th style="min-width: 320px;">Deskripsi Lengkap</th>
                                <th class="text-nowrap">Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ideas as $idea)
                                <tr>
                                    <td>
                                        <div class="fw-semibold"><i class="bi bi-briefcase me-1 text-primary"></i>{{ $idea->name }}</div>
                                        <div class="text-muted small">{{ $idea->slug }}</div>
                                    </td>
                                    <td class="small text-muted">
                                        <div>Modal: Rp{{ number_format($idea->capital_min, 0, ',', '.') }}@if($idea->capital_max)-Rp{{ number_format($idea->capital_max, 0, ',', '.') }}@endif</div>
                                        <div>Waktu: {{ $idea->free_time_min_hours }}@if($idea->free_time_max_hours)-{{ $idea->free_time_max_hours }}@endif jam/minggu</div>
                                        <div>Kategori: {{ implode(', ', $idea->suitable_locations ?? []) }}</div>
                                    </td>
                                    <td>
                                        <form id="idea-form-{{ $idea->id }}" method="POST" action="{{ route('admin.business-ideas.update', ['businessIdea' => $idea, 'page' => $ideas->currentPage()]) }}">
                                            @csrf
                                            @method('PUT')
                                            <textarea class="form-control" name="description" rows="4" maxlength="5000" placeholder="Isi deskripsi lengkap usaha...">{{ old('description', $idea->description) }}</textarea>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" id="is_active_{{ $idea->id }}" type="checkbox" name="is_active" value="1" @checked(old('is_active', $idea->is_active))>
                                                <label class="form-check-label small" for="is_active_{{ $idea->id }}">Aktif ditampilkan di rekomendasi</label>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        @if ($idea->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-primary" type="submit" form="idea-form-{{ $idea->id }}">
                                            <i class="bi bi-check2-circle me-1"></i>
                                            Simpan
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="admin-pagination">
                        {{ $ideas->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
