<?php

namespace App\Http\Controllers;

use App\Models\MicroBusinessIdea;
use Illuminate\Http\Request;

class MicroBusinessRecommendationController extends Controller
{
    public const LOCATIONS = [
        'perkotaan' => 'Perkotaan (Urban)',
        'pedesaan' => 'Pedesaan (Rural)',
        'pesisir' => 'Pesisir',
        'pegunungan' => 'Pegunungan/Dataran Tinggi',
        'kampus_kos' => 'Area Kampus/Kos',
        'pasar_komersial' => 'Pasar/Komersial',
        'rumahan' => 'Rumahan (Home-based)',
        'online' => 'Online (Tanpa lokasi spesifik)',
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
            'location.required' => 'Lokasi wajib dipilih.',
            'free_time_hours.required' => 'Waktu luang wajib diisi.',
        ]);

        $capital = (int) $validated['capital'];
        $location = (string) $validated['location'];
        $freeTimeHours = (int) $validated['free_time_hours'];

        // Bobot scoring (bisa diubah sesuai kebutuhan penelitian)
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

            $score = ($weights['capital'] * $capitalFit)
                + ($weights['time'] * $timeFit)
                + ($weights['location'] * $locationFit);

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
        $suitableLocations = $suitableLocations ?? [];
        $suitableLocations = array_values(array_filter(array_map('strval', $suitableLocations)));

        if (count($suitableLocations) === 0) {
            // Jika ide tidak membatasi lokasi, cocok sedang (sensitif lokasi tapi tetap memungkinkan).
            return 0.6;
        }

        return in_array($location, $suitableLocations, true) ? 1.0 : 0.0;
    }
}

