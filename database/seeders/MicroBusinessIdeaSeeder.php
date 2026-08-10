<?php

namespace Database\Seeders;

use App\Models\BusinessMasterOption;
use App\Models\Criterion;
use App\Models\MicroBusinessIdea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MicroBusinessIdeaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = $this->seedCriteria();
        $ideas = $this->loadIdeasFromCsv(
            database_path('seeders/data/micro_business_ideas.csv')
        );

        $seededSlugs = [];

        foreach ($ideas as $idea) {
            $slug = Str::slug($idea['name']);
            $seededSlugs[] = $slug;

            $scores = $idea['scores'];
            unset($idea['scores']);

            $businessIdea = MicroBusinessIdea::updateOrCreate(
                ['slug' => $slug],
                [
                    ...$idea,
                    'slug' => $slug,
                    'is_active' => true,
                ]
            );

            $this->syncScores($businessIdea, $criteria, $scores);
        }

        // Menjaga dataset konsisten dengan CSV: ide di luar CSV dinonaktifkan.
        if (count($seededSlugs) > 0) {
            MicroBusinessIdea::query()
                ->whereNotIn('slug', $seededSlugs)
                ->update(['is_active' => false]);
        }
    }

    /**
     * @return array<string,Criterion>
     */
    private function seedCriteria(): array
    {
        $criteria = [
            'modal' => ['name' => 'Modal', 'weight' => 0.45, 'type' => 'cost', 'sort_order' => 1],
            'lokasi' => ['name' => 'Lokasi', 'weight' => 0.30, 'type' => 'benefit', 'sort_order' => 2],
            'waktu' => ['name' => 'Waktu', 'weight' => 0.25, 'type' => 'benefit', 'sort_order' => 3],
        ];

        return collect($criteria)
            ->mapWithKeys(fn (array $criterion, string $code) => [
                $code => Criterion::updateOrCreate(
                    ['code' => $code],
                    [
                        ...$criterion,
                        'is_active' => true,
                    ]
                ),
            ])
            ->all();
    }

    /**
     * @param  array<string,Criterion>  $criteria
     * @param  array<string,int>  $scores
     */
    private function syncScores(MicroBusinessIdea $idea, array $criteria, array $scores): void
    {
        foreach ($scores as $code => $score) {
            if (! isset($criteria[$code])) {
                continue;
            }

            $idea->scores()->updateOrCreate(
                ['criterion_id' => $criteria[$code]->id],
                ['score' => $score]
            );
        }
    }

    /**
     * CSV kolom yang dipakai:
     * - namausaha -> name
     * - modal -> capital_min (dianggap modal minimal)
     * - lokasi -> suitable_locations (mapping)
     * - waktu -> free_time_* (mapping)
     */
    private function loadIdeasFromCsv(string $path): array
    {
        if (! File::exists($path)) {
            throw new \RuntimeException("CSV seeder tidak ditemukan: {$path}");
        }

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $header = null;
        $ideas = [];

        foreach ($file as $row) {
            if (! is_array($row)) {
                continue;
            }

            $row = array_map(
                fn ($v) => $v === null ? null : trim((string) $v),
                $row
            );

            // Kadang \SplFileObject menghasilkan [null] di akhir file.
            if (count($row) === 1 && ($row[0] === null || $row[0] === '')) {
                continue;
            }

            if ($header === null) {
                $header = array_map(fn ($h) => trim((string) $h), $row);
                if (isset($header[0])) {
                    $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
                }

                continue;
            }

            $assoc = [];
            foreach ($header as $i => $key) {
                $assoc[$key] = $row[$i] ?? null;
            }

            $name = trim((string) ($assoc['namausaha'] ?? ''));
            if ($name === '') {
                continue;
            }

            $modalEstimate = (int) preg_replace('/[^\d]/', '', (string) ($assoc['modal'] ?? '0'));
            $modalMinRaw = (string) ($assoc['modal_min'] ?? '');
            $modalMin = $modalMinRaw !== ''
                ? (int) preg_replace('/[^\d]/', '', $modalMinRaw)
                : $this->estimateModalMinFromScore($modalEstimate, (string) ($assoc['skormodal'] ?? ''));

            $lokasiRaw = trim((string) ($assoc['kategori_usaha'] ?? $assoc['lokasi'] ?? $assoc['kategori'] ?? ''));
            $waktuRaw = trim((string) ($assoc['waktu'] ?? ''));
            $capitalScore = $this->scoreToInt((string) ($assoc['skormodal'] ?? ''));
            $locationScore = $this->scoreToInt((string) ($assoc['skorlokasi'] ?? ''));
            $timeScore = $this->scoreToInt((string) ($assoc['skorwaktu'] ?? ''));
            $capitalScore = $capitalScore > 0 ? $capitalScore : $this->scoreFromModal($modalEstimate);
            $locationScore = $locationScore > 0 ? $locationScore : $this->scoreFromLocation($lokasiRaw);
            $timeScore = $timeScore > 0 ? $timeScore : $this->scoreFromTime($waktuRaw);

            [$freeTimeMin, $freeTimeMax] = $this->mapWaktuToRange($waktuRaw);
            $suitableLocations = $this->mapLokasiToCodes($lokasiRaw);

            $ideas[] = [
                'name' => $name,
                'description' => $this->buildDescription($modalMin, $modalEstimate, $lokasiRaw, $waktuRaw),
                'capital_min' => max(0, $modalMin),
                'capital_estimate' => $modalEstimate,
                'capital_max' => null,
                'free_time_min_hours' => $freeTimeMin,
                'free_time_max_hours' => $freeTimeMax,
                'suitable_locations' => $suitableLocations,
                'location_label' => $lokasiRaw,
                'time_label' => $waktuRaw,
                'scores' => [
                    'modal' => $capitalScore,
                    'lokasi' => $locationScore,
                    'waktu' => $timeScore,
                ],
            ];
        }

        return $ideas;
    }

    private function mapLokasiToCodes(string $lokasi): ?array
    {
        $lokasi = Str::lower(trim($lokasi));

        return match ($lokasi) {
            'online' => ['online'],
            'rumah' => ['rumahan'],
            'jalan', 'kios' => ['offline'],
            // "Fleksibel" (atau tidak dikenal) -> hybrid karena dapat berjalan online dan offline.
            default => ['hybrid'],
        };
    }

    private function scoreToInt(string $value): int
    {
        return max(0, min(255, (int) preg_replace('/[^\d]/', '', $value)));
    }

    private function scoreFromModal(int $modalEstimate): int
    {
        $score = $this->masterScoreFromRange(BusinessMasterOption::TYPE_CAPITAL, $modalEstimate);

        if ($score !== null) {
            return $score;
        }

        return match (true) {
            $modalEstimate <= 500_000 => 4,
            $modalEstimate <= 1_500_000 => 3,
            $modalEstimate <= 3_000_000 => 2,
            default => 1,
        };
    }

    private function scoreFromLocation(string $location): int
    {
        $score = $this->masterScore(BusinessMasterOption::TYPE_LOCATION, $location);

        if ($score !== null) {
            return $score;
        }

        return match (Str::lower(trim($location))) {
            'online', 'fleksibel', 'hybrid' => 4,
            'rumah', 'rumahan' => 2,
            default => 3,
        };
    }

    private function scoreFromTime(string $time): int
    {
        $score = $this->masterScore(BusinessMasterOption::TYPE_TIME, $time);

        if ($score !== null) {
            return $score;
        }

        return match (Str::lower(trim($time))) {
            'fleksibel' => 4,
            'rendah' => 1,
            'sedang' => 2,
            'tinggi' => 3,
            default => 2,
        };
    }

    private function mapWaktuToRange(string $waktu): array
    {
        $waktu = Str::lower(trim($waktu));

        return match ($waktu) {
            'rendah' => [2, 6],
            'sedang' => [7, 14],
            'tinggi' => [15, 30],
            // "Fleksibel" (atau tidak dikenal) -> tidak dibatasi.
            default => [0, null],
        };
    }

    private function estimateModalMinFromScore(int $modalEstimate, string $score): int
    {
        if ($modalEstimate <= 0) {
            return 0;
        }

        $score = trim($score);
        $ratio = match ($score) {
            '4' => 0.50,
            '3' => 0.65,
            '2' => 0.80,
            '1' => 0.90,
            default => 0.75,
        };

        $min = (int) round($modalEstimate * $ratio);
        $min = max(50_000, $min);

        // Biar rapi: bulatkan ke kelipatan 50rb.
        $min = (int) (round($min / 50_000) * 50_000);

        return min($min, $modalEstimate);
    }

    private function masterScore(string $type, string $label): ?int
    {
        $normalized = Str::lower(trim($label));

        if ($normalized === '') {
            return null;
        }

        $option = BusinessMasterOption::query()
            ->active()
            ->ofType($type)
            ->whereRaw('LOWER(label) = ?', [$normalized])
            ->first();

        return $option?->score;
    }

    private function masterScoreFromRange(string $type, int $value): ?int
    {
        $option = BusinessMasterOption::query()
            ->active()
            ->ofType($type)
            ->where(function ($query) use ($value) {
                $query->whereNull('value_min')
                    ->orWhere('value_min', '<=', $value);
            })
            ->where(function ($query) use ($value) {
                $query->whereNull('value_max')
                    ->orWhere('value_max', '>=', $value);
            })
            ->orderBy('sort_order')
            ->first();

        return $option?->score;
    }

    private function buildDescription(int $modalMin, int $modalEstimate, string $lokasi, string $waktu): string
    {
        $parts = [];

        if ($modalMin <= 0) {
            $parts[] = 'Modal minimal: Rp0';
        } else {
            $parts[] = 'Modal minimal: Rp'.number_format($modalMin, 0, ',', '.');
        }

        if ($modalEstimate > 0 && $modalEstimate !== $modalMin) {
            $parts[] = 'Estimasi modal: Rp'.number_format($modalEstimate, 0, ',', '.');
        }

        $lokasi = trim($lokasi);
        if ($lokasi !== '') {
            $parts[] = 'Lokasi: '.$lokasi;
        }

        $waktu = trim($waktu);
        if ($waktu !== '') {
            $parts[] = 'Waktu: '.$waktu;
        }

        return implode('. ', $parts).'.';
    }
}
