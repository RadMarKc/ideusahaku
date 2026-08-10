<?php

namespace App\Http\Controllers;

use App\Models\MicroBusinessIdea;
use App\Services\RecommendationDataService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MicroBusinessRecommendationController extends Controller
{
    public function __construct(private readonly RecommendationDataService $data) {}

    public function dashboard(): View
    {
        $criteria = $this->data->criteria();
        $formula = $this->data->formula();

        return view('dashboard', [
            'ideaCount' => MicroBusinessIdea::query()->where('is_active', true)->count(),
            'locationCount' => $this->data->locations()->count(),
            'timeCount' => $this->data->times()->count(),
            'criteriaCount' => $criteria->count(),
            'criteria' => $criteria,
            'formula' => $formula,
            'locations' => $this->data->locations(),
            'times' => $this->data->times(),
        ]);
    }

    public function form()
    {
        $locations = $this->data->locations();
        $times = $this->data->times();
        $criteria = $this->data->criteria();
        $formula = $this->data->formula();

        return view('rekomendasi.form', [
            'locations' => $locations,
            'times' => $times,
            'criteria' => $criteria,
            'formula' => $formula,
            'input' => [
                'capital' => old('capital'),
                'location' => old('location'),
                'time' => old('time'),
            ],
            'recommendations' => null,
        ]);
    }

    public function recommend(Request $request)
    {
        $locations = $this->data->locations();
        $times = $this->data->times();
        $criteria = $this->data->criteria();
        $formula = $this->data->formula();

        $validated = $request->validate([
            'capital' => ['required', 'integer', 'min:0'],
            'location' => ['required', 'string', Rule::in($locations->keys()->all())],
            'time' => ['required', 'string', Rule::in($times->keys()->all())],
        ], [
            'capital.required' => 'Modal wajib diisi.',
            'location.required' => 'Kategori wajib dipilih.',
            'time.required' => 'Waktu wajib dipilih.',
        ]);

        $capital = (int) $validated['capital'];
        $location = (string) $validated['location'];
        $time = (string) $validated['time'];
        $selectedLocation = $locations->get($location);
        $selectedTime = $times->get($time);
        $selectedLocationLabel = (string) ($selectedLocation?->label ?? $location);
        $selectedTimeLabel = (string) ($selectedTime?->label ?? $time);

        $weights = [
            'modal' => (float) $formula->modal_weight,
            'lokasi' => (float) $formula->location_weight,
            'waktu' => (float) $formula->time_weight,
        ];

        $weightTotal = array_sum($weights);

        if ($weightTotal > 0) {
            $weights = array_map(
                fn (float $weight): float => $weight / $weightTotal,
                $weights
            );
        } else {
            $weights = [
                'modal' => 0.45,
                'lokasi' => 0.30,
                'waktu' => 0.25,
            ];
        }

        $ideas = MicroBusinessIdea::query()
            ->with([
                'scores' => fn ($query) => $query->select('id', 'micro_business_idea_id', 'criterion_id', 'score'),
                'scores.criterion' => fn ($query) => $query->select('id', 'code'),
            ])
            ->where('is_active', true)
            ->get([
                'id',
                'name',
                'slug',
                'description',
                'capital_min',
                'capital_estimate',
                'free_time_min_hours',
                'free_time_max_hours',
                'suitable_locations',
                'location_label',
                'time_label',
            ]);

        $maxScores = [
            'modal' => max(1, (int) $ideas->max(fn (MicroBusinessIdea $idea) => $idea->capital_score)),
            'lokasi' => max(1, (int) $ideas->max(fn (MicroBusinessIdea $idea) => $idea->location_score)),
            'waktu' => max(1, (int) $ideas->max(fn (MicroBusinessIdea $idea) => $idea->time_score)),
        ];

        $scored = $ideas->map(function (MicroBusinessIdea $idea) use ($capital, $selectedLocationLabel, $selectedTimeLabel, $weights, $maxScores) {
            $capitalValue = $this->capitalScore($idea, $capital);
            $locationValue = $this->labelScore($idea->location_score, (string) $idea->location_label, $selectedLocationLabel);
            $timeValue = $this->labelScore($idea->time_score, (string) $idea->time_label, $selectedTimeLabel);

            $criteria = [
                'modal' => $capitalValue / $maxScores['modal'],
                'lokasi' => $locationValue / $maxScores['lokasi'],
                'waktu' => $timeValue / $maxScores['waktu'],
            ];

            $score = $this->weightedProductScore($criteria, $weights);

            return [
                'idea' => $idea,
                'score' => round($score * 100, 2),
                'breakdown' => [
                    'modal' => round($criteria['modal'] * 100, 2),
                    'lokasi' => round($criteria['lokasi'] * 100, 2),
                    'waktu' => round($criteria['waktu'] * 100, 2),
                ],
            ];
        })->sortByDesc('score')->sortByDesc(fn ($row) => $row['idea']->total_score)->values();

        return view('rekomendasi.form', [
            'locations' => $locations,
            'times' => $times,
            'criteria' => $criteria,
            'formula' => $formula,
            'input' => [
                'capital' => $capital,
                'location' => $location,
                'time' => $time,
            ],
            'selectedLocation' => $selectedLocation,
            'selectedTime' => $selectedTime,
            'recommendations' => $scored->take(10),
        ]);
    }

    public function show(MicroBusinessIdea $businessIdea): View
    {
        abort_unless($businessIdea->is_active, 404);
        $businessIdea->loadMissing([
            'scores' => fn ($query) => $query->select('id', 'micro_business_idea_id', 'criterion_id', 'score'),
            'scores.criterion' => fn ($query) => $query->select('id', 'code'),
        ]);

        return view('rekomendasi.detail', [
            'idea' => $businessIdea,
            'locations' => $this->data->locations(),
            'criteria' => $this->data->criteria(),
        ]);
    }

    /**
     * Weighted Product Method: S_i = product(x_ij ^ w_j).
     * Nilai kriteria memakai skala 0..1, lalu dikonversi menjadi persen pada output.
     */
    private function weightedProductScore(array $criteria, array $weights): float
    {
        $score = 1.0;

        foreach ($weights as $criterion => $weight) {
            $value = (float) ($criteria[$criterion] ?? 0.0);
            $score *= pow(max(0.01, min(1.0, $value)), (float) $weight);
        }

        return max(0.0, min(1.0, $score));
    }

    private function capitalScore(MicroBusinessIdea $idea, int $capital): float
    {
        $score = max(1, (int) $idea->capital_score);
        $minimum = max(0, (int) $idea->capital_min);

        if ($minimum > 0 && $capital < $minimum) {
            return max(1.0, $score * ($capital / $minimum));
        }

        return (float) $score;
    }

    private function labelScore(int $score, string $ideaLabel, string $selectedLabel): float
    {
        $score = max(1, $score);

        if ($this->sameLabel($ideaLabel, $selectedLabel) || $this->isFlexible($ideaLabel) || $this->isFlexible($selectedLabel)) {
            return (float) $score;
        }

        return 1.0;
    }

    private function sameLabel(string $left, string $right): bool
    {
        return mb_strtolower(trim($left)) === mb_strtolower(trim($right));
    }

    private function isFlexible(string $label): bool
    {
        return in_array(mb_strtolower(trim($label)), ['fleksibel', 'hybrid'], true);
    }
}
