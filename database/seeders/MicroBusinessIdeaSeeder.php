<?php

namespace Database\Seeders;

use App\Models\MicroBusinessIdea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MicroBusinessIdeaSeeder extends Seeder
{
    public function run(): void
    {
        $ideas = $this->loadIdeasFromCsv(
            database_path('seeders/data/micro_business_ideas.csv')
        );

        $seededSlugs = [];

        foreach ($ideas as $idea) {
            $slug = Str::slug($idea['name']);
            $seededSlugs[] = $slug;

            MicroBusinessIdea::updateOrCreate(
                ['slug' => $slug],
                [
                    ...$idea,
                    'slug' => $slug,
                    'is_active' => true,
                ]
            );
        }

        // Menjaga dataset konsisten dengan CSV: ide di luar CSV dinonaktifkan.
        if (count($seededSlugs) > 0) {
            MicroBusinessIdea::query()
                ->whereNotIn('slug', $seededSlugs)
                ->update(['is_active' => false]);
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
        if (!File::exists($path)) {
            throw new \RuntimeException("CSV seeder tidak ditemukan: {$path}");
        }

        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $header = null;
        $ideas = [];

        foreach ($file as $row) {
            if (!is_array($row)) {
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

            $lokasiRaw = trim((string) ($assoc['lokasi'] ?? ''));
            $waktuRaw = trim((string) ($assoc['waktu'] ?? ''));

            [$freeTimeMin, $freeTimeMax] = $this->mapWaktuToRange($waktuRaw);
            $suitableLocations = $this->mapLokasiToCodes($lokasiRaw);

            $ideas[] = [
                'name' => $name,
                'description' => $this->buildDescription($modalMin, $modalEstimate, $lokasiRaw, $waktuRaw),
                'capital_min' => max(0, $modalMin),
                'capital_max' => null,
                'free_time_min_hours' => $freeTimeMin,
                'free_time_max_hours' => $freeTimeMax,
                'suitable_locations' => $suitableLocations,
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
            'jalan' => ['pasar_komersial', 'perkotaan'],
            'kios' => ['pasar_komersial'],
            // "Fleksibel" (atau tidak dikenal) -> null agar dianggap tidak membatasi lokasi.
            default => null,
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

    private function buildDescription(int $modalMin, int $modalEstimate, string $lokasi, string $waktu): string
    {
        $parts = [];

        if ($modalMin <= 0) {
            $parts[] = 'Modal minimal: Rp0';
        } else {
            $parts[] = 'Modal minimal: Rp' . number_format($modalMin, 0, ',', '.');
        }

        if ($modalEstimate > 0 && $modalEstimate !== $modalMin) {
            $parts[] = 'Estimasi modal: Rp' . number_format($modalEstimate, 0, ',', '.');
        }

        $lokasi = trim($lokasi);
        if ($lokasi !== '') {
            $parts[] = 'Lokasi: ' . $lokasi;
        }

        $waktu = trim($waktu);
        if ($waktu !== '') {
            $parts[] = 'Waktu: ' . $waktu;
        }

        return implode('. ', $parts) . '.';
    }
}
