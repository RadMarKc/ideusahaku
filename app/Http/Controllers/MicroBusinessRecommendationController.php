<?php

namespace App\Http\Controllers;

use App\Models\MicroBusinessIdea;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MicroBusinessRecommendationController extends Controller
{
    public const LOCATIONS = [
        'online' => 'Online',
        'offline' => 'Offline',
        'rumahan' => 'Rumahan',
        'hybrid' => 'Hybrid (Online + Offline)',
    ];

    public function form()
    {
        return view('rekomendasi.form', [
            'locations' => self::LOCATIONS,
            'input' => [
                'capital' => old('capital'),
                'location' => old('location'),
                'free_time_hours' => old('free_time_hours'),
            ],
            'recommendations' => null,
        ]);
    }

    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'capital' => ['required', 'integer', 'min:0'],
            'location' => ['required', 'string', 'in:' . implode(',', array_keys(self::LOCATIONS))],
            'free_time_hours' => ['required', 'integer', 'min:0', 'max:168'],
        ], [
            'capital.required' => 'Modal wajib diisi.',
            'location.required' => 'Kategori wajib dipilih.',
            'free_time_hours.required' => 'Waktu luang wajib diisi.',
        ]);

        $capital = (int) $validated['capital'];
        $location = (string) $validated['location'];
        $freeTimeHours = (int) $validated['free_time_hours'];

        // Bobot kriteria untuk Weighted Product Method.
        $weights = [
            'capital' => 0.45,
            'time' => 0.35,
            'location' => 0.20,
        ];

        $ideas = MicroBusinessIdea::query()
            ->where('is_active', true)
            ->get();

        $scored = $ideas->map(function (MicroBusinessIdea $idea) use ($capital, $location, $freeTimeHours, $weights) {
            $capitalFit = $this->rangeFit($capital, (int) $idea->capital_min, $idea->capital_max === null ? null : (int) $idea->capital_max);
            $timeFit = $this->rangeFit($freeTimeHours, (int) $idea->free_time_min_hours, $idea->free_time_max_hours === null ? null : (int) $idea->free_time_max_hours);
            $locationFit = $this->locationFit($location, $idea->suitable_locations);

            $score = $this->weightedProductScore([
                'capital' => $capitalFit,
                'time' => $timeFit,
                'location' => $locationFit,
            ], $weights);

            return [
                'idea' => $idea,
                'score' => round($score * 100, 2),
                'breakdown' => [
                    'capital_fit' => round($capitalFit * 100, 2),
                    'time_fit' => round($timeFit * 100, 2),
                    'location_fit' => round($locationFit * 100, 2),
                ],
            ];
        })->sortByDesc('score')->values();

        return view('rekomendasi.form', [
            'locations' => self::LOCATIONS,
            'input' => [
                'capital' => $capital,
                'location' => $location,
                'free_time_hours' => $freeTimeHours,
            ],
            'recommendations' => $scored->take(10),
        ]);
    }

    public function show(MicroBusinessIdea $businessIdea): View
    {
        abort_unless($businessIdea->is_active, 404);

        return view('rekomendasi.detail', [
            'idea' => $businessIdea,
            'locations' => self::LOCATIONS,
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

    /**
     * Kecocokan nilai terhadap rentang min..max.
     * - Jika di dalam rentang: 1.0
     * - Jika di luar: turun proporsional terhadap jarak dari batas.
     */
    private function rangeFit(int $value, int $min, ?int $max): float
    {
        if ($value < $min) {
            $denom = max($min, 1);
            return max(0.0, 1.0 - (($min - $value) / $denom));
        }

        if ($max !== null && $value > $max) {
            $denom = max($max, 1);
            return max(0.0, 1.0 - (($value - $max) / $denom));
        }

        return 1.0;
    }

    private function locationFit(string $location, ?array $suitableLocations): float
    {
        $location = $this->normalizeCategory($location);
        $suitableLocations = $suitableLocations ?? [];
        $suitableLocations = array_values(array_filter(array_map(
            fn ($value) => $this->normalizeCategory((string) $value),
            $suitableLocations
        )));

        if (count($suitableLocations) === 0) {
            // Jika ide tidak membatasi kategori, cocok sedang tapi tetap memungkinkan.
            return 0.6;
        }

        return in_array($location, $suitableLocations, true) ? 1.0 : 0.0;
    }

    private function normalizeCategory(string $category): string
    {
        return match ($category) {
            'perkotaan',
            'pedesaan',
            'pesisir',
            'pegunungan',
            'kampus_kos',
            'pasar_komersial' => 'offline',
            default => $category,
        };
    }
}
