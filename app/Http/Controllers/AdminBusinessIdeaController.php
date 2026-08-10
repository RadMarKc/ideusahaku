<?php

namespace App\Http\Controllers;

use App\Models\BusinessMasterOption;
use App\Models\Criterion;
use App\Models\FormulaSetting;
use App\Models\MicroBusinessIdea;
use App\Services\BusinessIdeaImportService;
use App\Services\RecommendationDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminBusinessIdeaController extends Controller
{
    public function __construct(private readonly RecommendationDataService $data) {}

    public function index(): View
    {
        return view('admin.business-ideas.index', [
            'ideas' => MicroBusinessIdea::query()
                ->with([
                    'scores' => fn ($query) => $query->select('id', 'micro_business_idea_id', 'criterion_id', 'score'),
                    'scores.criterion' => fn ($query) => $query->select('id', 'code'),
                ])
                ->orderBy('name')
                ->paginate(15),
        ]);
    }

    public function categories(): View
    {
        return $this->masterView('all');
    }

    public function capitalMaster(): View
    {
        return $this->masterView('modal');
    }

    public function categoryMaster(): View
    {
        return $this->masterView('kategori');
    }

    public function timeMaster(): View
    {
        return $this->masterView('waktu');
    }

    public function formulaMaster(): View
    {
        return view('admin.formula.index', [
            'formula' => FormulaSetting::current(),
        ]);
    }

    public function criteriaMaster(): View
    {
        return view('admin.criteria.index', [
            'criteria' => Criterion::query()
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function storeMasterOption(Request $request): RedirectResponse
    {
        $validated = $this->validateMasterOption($request);

        BusinessMasterOption::query()->create($validated);

        return $this->redirectToMasterSection($validated['type'])
            ->with('status', 'Master berhasil ditambahkan.');
    }

    public function updateMasterOption(Request $request, BusinessMasterOption $masterOption): RedirectResponse
    {
        $validated = $this->validateMasterOption($request, $masterOption);

        $masterOption->update($validated);

        return $this->redirectToMasterSection($validated['type'])
            ->with('status', 'Master berhasil diperbarui.');
    }

    public function destroyMasterOption(BusinessMasterOption $masterOption): RedirectResponse
    {
        $type = $masterOption->type;
        $masterOption->delete();

        return $this->redirectToMasterSection($type)
            ->with('status', 'Master berhasil dihapus.');
    }

    public function updateCriterion(Request $request, Criterion $criterion): RedirectResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('criteria', 'code')->ignore($criterion->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'numeric', 'min:0', 'max:999.99'],
            'type' => ['required', Rule::in(['benefit', 'cost'])],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $criterion->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'weight' => $validated['weight'],
            'type' => $validated['type'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.master.criteria.index')
            ->with('status', 'Kriteria berhasil diperbarui.');
    }

    public function updateFormulaSetting(Request $request, FormulaSetting $formulaSetting): RedirectResponse
    {
        $validated = $request->validate([
            'modal_weight' => ['required', 'numeric', 'min:0', 'max:9.99'],
            'location_weight' => ['required', 'numeric', 'min:0', 'max:9.99'],
            'time_weight' => ['required', 'numeric', 'min:0', 'max:9.99'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $weightTotal = (float) $validated['modal_weight']
            + (float) $validated['location_weight']
            + (float) $validated['time_weight'];

        if ($weightTotal <= 0) {
            return back()
                ->withInput()
                ->withErrors(['modal_weight' => 'Total bobot formula harus lebih dari 0.']);
        }

        $formulaSetting->update([
            'modal_weight' => $validated['modal_weight'],
            'location_weight' => $validated['location_weight'],
            'time_weight' => $validated['time_weight'],
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.master.formula.index')
            ->with('status', 'Formula berhasil diperbarui.');
    }

    private function masterView(string $section): View
    {
        $capitals = BusinessMasterOption::query()
            ->active()
            ->ofType(BusinessMasterOption::TYPE_CAPITAL)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $locations = $this->data->locations()->values();

        $times = $this->data->times()->values();

        return view('admin.categories.index', [
            'capitals' => $capitals,
            'locations' => $locations,
            'times' => $times,
            'section' => $section,
        ]);
    }

    public function import(Request $request, BusinessIdeaImportService $importer): RedirectResponse
    {
        $validated = $request->validate([
            'business_file' => ['required', 'file', 'mimes:csv,txt,xlsx', 'max:5120'],
        ], [
            'business_file.required' => 'File CSV/Excel wajib diunggah.',
            'business_file.mimes' => 'Format file harus CSV atau Excel .xlsx.',
        ]);

        $file = $validated['business_file'];
        $result = $importer->import(
            $file->getRealPath(),
            $file->getClientOriginalExtension()
        );

        return redirect()
            ->route('admin.business-ideas.index')
            ->with('status', "Import selesai. {$result['imported']} data usaha diproses.");
    }

    public function template(): StreamedResponse
    {
        $rows = [
            ['id', 'namausaha', 'modal', 'modal_min', 'kategori_usaha', 'waktu', 'deskripsi'],
            ['1', 'Reseller Baju', '500000', '250000', 'Online', 'Fleksibel', 'Usaha jual beli produk fashion melalui marketplace.'],
            ['2', 'Jasa Pengetikan', '2000000', '1600000', 'Rumah', 'Sedang', 'Jasa pengetikan dokumen dari rumah.'],
        ];

        return response()->streamDownload(function () use ($rows) {
            $output = fopen('php://output', 'w');

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, 'template-data-usaha.csv', [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }

    public function update(Request $request, MicroBusinessIdea $businessIdea): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'capital_estimate' => ['required', 'integer', 'min:0'],
            'capital_min' => ['required', 'integer', 'min:0'],
            'location_label' => ['nullable', 'string', 'max:255'],
            'time_label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $locationLabel = trim((string) ($validated['location_label'] ?? ''));
        $timeLabel = trim((string) ($validated['time_label'] ?? ''));
        $slug = Str::slug($validated['name']);

        $slugExists = MicroBusinessIdea::query()
            ->where('slug', $slug)
            ->whereKeyNot($businessIdea->id)
            ->exists();

        if ($slugExists) {
            return back()
                ->withInput()
                ->withErrors(['name' => 'Nama usaha sudah digunakan oleh data lain.']);
        }

        $businessIdea->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'capital_estimate' => $validated['capital_estimate'],
            'capital_min' => $validated['capital_min'],
            'capital_max' => null,
            'location_label' => $locationLabel,
            'time_label' => $timeLabel,
            'free_time_min_hours' => $this->timeMinHours($timeLabel),
            'free_time_max_hours' => $this->timeMaxHours($timeLabel),
            'suitable_locations' => $this->locationCodes($locationLabel),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->syncScores($businessIdea, [
            'modal' => $this->scoreForCapital((int) $validated['capital_estimate']),
            'lokasi' => $this->scoreForOptionLabel(BusinessMasterOption::TYPE_LOCATION, $locationLabel),
            'waktu' => $this->scoreForOptionLabel(BusinessMasterOption::TYPE_TIME, $timeLabel),
        ]);

        return redirect()
            ->route('admin.business-ideas.index', ['page' => $request->integer('page', 1)])
            ->with('status', 'Data usaha berhasil diperbarui.');
    }

    public function destroy(Request $request, MicroBusinessIdea $businessIdea): RedirectResponse
    {
        $businessIdea->delete();

        return redirect()
            ->route('admin.business-ideas.index', ['page' => $request->integer('page', 1)])
            ->with('status', 'Data usaha berhasil dihapus.');
    }

    public function destroyAll(Request $request): RedirectResponse
    {
        $deletedCount = MicroBusinessIdea::query()->count();
        MicroBusinessIdea::query()->delete();

        return redirect()
            ->route('admin.business-ideas.index')
            ->with('status', "Semua data usaha berhasil dihapus. {$deletedCount} data terhapus.");
    }

    private function locationCodes(string $location): array
    {
        return match (Str::lower(trim($location))) {
            'online' => ['online'],
            'rumah', 'rumahan' => ['rumahan'],
            'jalan', 'kios', 'offline' => ['offline'],
            default => ['hybrid'],
        };
    }

    /**
     * @return array<string,mixed>
     */
    private function validateMasterOption(Request $request, ?BusinessMasterOption $masterOption = null): array
    {
        $type = (string) $request->input('type');

        $validated = $request->validate([
            'type' => ['required', Rule::in([
                BusinessMasterOption::TYPE_CAPITAL,
                BusinessMasterOption::TYPE_LOCATION,
                BusinessMasterOption::TYPE_TIME,
            ])],
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('business_master_options', 'code')
                    ->where('type', $type)
                    ->ignore($masterOption?->id),
            ],
            'label' => ['required', 'string', 'max:255'],
            'score' => ['required', 'integer', 'min:0', 'max:255'],
            'value_min' => ['nullable', 'integer', 'min:0'],
            'value_max' => ['nullable', 'integer', 'min:0'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($validated['type'] !== BusinessMasterOption::TYPE_CAPITAL) {
            $validated['value_min'] = null;
            $validated['value_max'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function redirectToMasterSection(string $type): RedirectResponse
    {
        return match ($type) {
            BusinessMasterOption::TYPE_CAPITAL => redirect()->route('admin.master.capitals.index'),
            BusinessMasterOption::TYPE_TIME => redirect()->route('admin.master.times.index'),
            default => redirect()->route('admin.master.categories.index'),
        };
    }

    private function scoreForCapital(int $capital): int
    {
        $option = BusinessMasterOption::query()
            ->active()
            ->ofType(BusinessMasterOption::TYPE_CAPITAL)
            ->where(function ($query) use ($capital) {
                $query->whereNull('value_min')
                    ->orWhere('value_min', '<=', $capital);
            })
            ->where(function ($query) use ($capital) {
                $query->whereNull('value_max')
                    ->orWhere('value_max', '>=', $capital);
            })
            ->orderByDesc('score')
            ->first();

        return (int) ($option?->score ?? 1);
    }

    private function scoreForOptionLabel(string $type, string $label): int
    {
        $normalized = Str::lower(trim($label));

        $option = BusinessMasterOption::query()
            ->active()
            ->ofType($type)
            ->where(function ($query) use ($normalized) {
                $query->whereRaw('LOWER(label) = ?', [$normalized])
                    ->orWhereRaw('LOWER(code) = ?', [$normalized]);
            })
            ->first();

        return (int) ($option?->score ?? 1);
    }

    /**
     * @param  array<string,int>  $scores
     */
    private function syncScores(MicroBusinessIdea $idea, array $scores): void
    {
        $criteria = Criterion::query()
            ->whereIn('code', array_keys($scores))
            ->get()
            ->keyBy('code');

        foreach ($scores as $code => $score) {
            $criterion = $criteria->get($code);

            if ($criterion === null) {
                continue;
            }

            $idea->scores()->updateOrCreate(
                ['criterion_id' => $criterion->id],
                ['score' => $score]
            );
        }
    }

    private function timeMinHours(string $time): int
    {
        return match (Str::lower(trim($time))) {
            'rendah' => 2,
            'sedang' => 7,
            'tinggi' => 15,
            default => 0,
        };
    }

    private function timeMaxHours(string $time): ?int
    {
        return match (Str::lower(trim($time))) {
            'rendah' => 6,
            'sedang' => 14,
            'tinggi' => 30,
            default => null,
        };
    }
}
