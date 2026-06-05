@extends('layouts.main')

@section('title', 'Dashboard Data Usaha')

@section('content')
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-md-center mb-4">
            <div>
                <h1 class="h3 mb-1"><i class="bi bi-database-check text-primary me-2"></i>Data Usaha</h1>
                <p class="text-muted mb-0">Upload data CSV/Excel sesuai format dataset usaha, lalu kelola data dari dashboard.</p>
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
                                <div class="form-text">Gunakan file CSV, TXT, atau Excel .xlsx dengan format kolom seperti contoh.</div>
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
                                    <td><code>id</code></td>
                                    <td>1</td>
                                </tr>
                                <tr>
                                    <td><code>namausaha</code></td>
                                    <td>Reseller Baju</td>
                                </tr>
                                <tr>
                                    <td><code>modal</code></td>
                                    <td>500000</td>
                                </tr>
                                <tr>
                                    <td><code>modal_min</code></td>
                                    <td>250000</td>
                                </tr>
                                <tr>
                                    <td><code>kategori_usaha</code></td>
                                    <td>Online</td>
                                </tr>
                                <tr>
                                    <td><code>waktu</code></td>
                                    <td>Fleksibel</td>
                                </tr>
                                <tr>
                                    <td><code>deskripsi</code></td>
                                    <td>Usaha jual beli produk fashion melalui marketplace.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mb-0">Kolom <code>id</code> boleh ada dan akan diabaikan saat import. Skor modal, kategori, dan waktu otomatis diambil dari menu <strong>Master</strong>. Kolom alternatif yang juga diterima: <code>nama_usaha</code>, <code>name</code>, <code>lokasi</code>, <code>kategori</code>, dan <code>description</code>.</p>
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
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small">{{ $ideas->total() }} data</span>
                    @if ($ideas->total() > 0)
                        <form method="POST" action="{{ route('admin.business-ideas.destroy-all') }}" onsubmit="return confirm('Hapus semua data usaha? Aksi ini tidak bisa dibatalkan.')" class="mb-0">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                <i class="bi bi-trash3 me-1"></i>
                                Delete All
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if ($ideas->isEmpty())
                    <p class="text-muted mb-0">Belum ada data usaha. Upload file CSV/Excel untuk mulai mengisi data.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                            <tr>
                                <th class="text-nowrap">ID</th>
                                <th style="min-width: 180px;">Nama Usaha</th>
                                <th class="text-nowrap">Modal Estimasi</th>
                                <th class="text-nowrap">Modal Min</th>
                                <th class="text-nowrap">Kategori Usaha</th>
                                <th class="text-nowrap">Waktu</th>
                                <th class="text-nowrap">Skor Modal</th>
                                <th class="text-nowrap">Skor Kategori</th>
                                <th class="text-nowrap">Skor Waktu</th>
                                <th class="text-nowrap">Total</th>
                                <th class="text-nowrap">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($ideas as $idea)
                                <tr>
                                    <td>{{ $idea->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-nowrap"><i class="bi bi-briefcase me-1 text-primary"></i>{{ $idea->name }}</div>
                                        <div class="text-muted small text-truncate" style="max-width: 260px;">{{ $idea->description ?: 'Belum ada deskripsi' }}</div>
                                        <div class="d-flex gap-2 mt-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.business-ideas.index', ['page' => $ideas->currentPage(), 'edit' => $idea->id]) }}#edit-idea-{{ $idea->id }}">
                                                <i class="bi bi-pencil-square me-1"></i>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.business-ideas.destroy', ['businessIdea' => $idea, 'page' => $ideas->currentPage()]) }}" onsubmit="return confirm('Hapus data usaha {{ $idea->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">
                                                    <i class="bi bi-trash me-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-nowrap">Rp{{ number_format($idea->capital_estimate, 0, ',', '.') }}</td>
                                    <td class="text-nowrap">Rp{{ number_format($idea->capital_min, 0, ',', '.') }}</td>
                                    <td class="text-nowrap">{{ $idea->location_label ?: implode(', ', $idea->suitable_locations ?? []) }}</td>
                                    <td class="text-nowrap">{{ $idea->time_label ?: '-' }}</td>
                                    <td class="text-center">{{ $idea->capital_score }}</td>
                                    <td class="text-center">{{ $idea->location_score }}</td>
                                    <td class="text-center">{{ $idea->time_score }}</td>
                                    <td class="text-center fw-semibold">{{ $idea->total_score }}</td>
                                    <td>
                                        @if ($idea->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                @if(request()->integer('edit') === $idea->id)
                                    <tr id="edit-idea-{{ $idea->id }}">
                                        <td colspan="11" class="bg-light">
                                            <form method="POST" action="{{ route('admin.business-ideas.update', ['businessIdea' => $idea, 'page' => $ideas->currentPage()]) }}" class="p-3">
                                                @csrf
                                                @method('PUT')
                                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                                    <div>
                                                        <h2 class="h6 mb-1">Edit {{ $idea->name }}</h2>
                                                        <div class="text-muted small">
                                                            Modal: Rp{{ number_format($idea->capital_estimate, 0, ',', '.') }} | Kategori: {{ $idea->location_label ?: '-' }} | Total Skor: {{ $idea->total_score }}
                                                        </div>
                                                    </div>
                                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.business-ideas.index', ['page' => $ideas->currentPage()]) }}">
                                                        Batal
                                                    </a>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="name_{{ $idea->id }}">Nama Usaha</label>
                                                        <input class="form-control" id="name_{{ $idea->id }}" type="text" name="name" value="{{ old('name', $idea->name) }}" maxlength="255" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="capital_estimate_{{ $idea->id }}">Modal</label>
                                                        <input class="form-control" id="capital_estimate_{{ $idea->id }}" type="number" name="capital_estimate" value="{{ old('capital_estimate', $idea->capital_estimate) }}" min="0" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label" for="capital_min_{{ $idea->id }}">Modal Min</label>
                                                        <input class="form-control" id="capital_min_{{ $idea->id }}" type="number" name="capital_min" value="{{ old('capital_min', $idea->capital_min) }}" min="0" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="location_label_{{ $idea->id }}">Kategori Usaha</label>
                                                        <input class="form-control" id="location_label_{{ $idea->id }}" type="text" name="location_label" value="{{ old('location_label', $idea->location_label) }}" maxlength="255" placeholder="Online/Rumah/Fleksibel">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="time_label_{{ $idea->id }}">Waktu</label>
                                                        <input class="form-control" id="time_label_{{ $idea->id }}" type="text" name="time_label" value="{{ old('time_label', $idea->time_label) }}" maxlength="255" placeholder="Rendah/Sedang/Tinggi/Fleksibel">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label" for="description_{{ $idea->id }}">Deskripsi Lengkap</label>
                                                        <textarea class="form-control" id="description_{{ $idea->id }}" name="description" rows="4" maxlength="5000" placeholder="Isi deskripsi lengkap usaha...">{{ old('description', $idea->description) }}</textarea>
                                                    </div>
                                                </div>

                                                <details class="mt-3">
                                                    <summary class="text-primary fw-semibold" style="cursor: pointer;">Nilai Kriteria</summary>
                                                    <div class="row g-3 mt-2">
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="capital_score_{{ $idea->id }}">Skor Modal</label>
                                                            <input class="form-control" id="capital_score_{{ $idea->id }}" type="number" name="capital_score" value="{{ old('capital_score', $idea->capital_score) }}" min="0" max="255" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="location_score_{{ $idea->id }}">Skor Kategori</label>
                                                            <input class="form-control" id="location_score_{{ $idea->id }}" type="number" name="location_score" value="{{ old('location_score', $idea->location_score) }}" min="0" max="255" required>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label class="form-label" for="time_score_{{ $idea->id }}">Skor Waktu</label>
                                                            <input class="form-control" id="time_score_{{ $idea->id }}" type="number" name="time_score" value="{{ old('time_score', $idea->time_score) }}" min="0" max="255" required>
                                                        </div>
                                                    </div>
                                                    <div class="alert alert-light border mt-3 mb-0 py-2">
                                                        Total skor saat ini: <strong>{{ $idea->total_score }}</strong>
                                                    </div>
                                                </details>

                                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" id="is_active_{{ $idea->id }}" type="checkbox" name="is_active" value="1" @checked(old('is_active', $idea->is_active))>
                                                        <label class="form-check-label" for="is_active_{{ $idea->id }}">Aktif ditampilkan di rekomendasi</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-check2-circle me-1"></i>
                                                        Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @endif
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
