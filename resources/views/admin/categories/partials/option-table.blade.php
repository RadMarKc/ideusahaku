<form method="POST" action="{{ route('admin.master-options.store') }}" class="row g-2 align-items-end mb-3">
    @csrf
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="col-md-4">
        <label class="form-label">{{ $labelTitle }}</label>
        <input class="form-control" name="label" placeholder="{{ $labelPlaceholder }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Kode</label>
        <input class="form-control" name="code" placeholder="{{ $codePlaceholder }}" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Skor</label>
        <input class="form-control" type="number" name="score" min="0" max="255" value="1" required>
    </div>
    <div class="col-md-2">
        <label class="form-label">Urutan</label>
        <input class="form-control" type="number" name="sort_order" min="0" value="{{ $options->count() + 1 }}" required>
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
            <th>{{ $labelTitle }}</th>
            <th>Kode</th>
            <th>Skor</th>
            <th>Urutan</th>
            <th>Status</th>
            <th class="text-end">Aksi</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($options as $option)
            <tr>
                <form method="POST" action="{{ route('admin.master-options.update', $option) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="{{ $option->type }}">
                    <td><input class="form-control form-control-sm" name="label" value="{{ $option->label }}" required></td>
                    <td><input class="form-control form-control-sm" name="code" value="{{ $option->code }}" required></td>
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
            <tr><td colspan="6" class="text-muted">Belum ada master {{ strtolower($labelTitle) }}.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
