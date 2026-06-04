<?php

namespace App\Services;

use App\Models\MicroBusinessIdea;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class BusinessIdeaImportService
{
    /**
     * @return array{imported:int,deactivated:int}
     */
    public function import(string $path, ?string $extension = null, bool $deactivateMissing = true): array
    {
        $rows = $this->rowsFromFile($path, $extension);
        $seededSlugs = [];
        $imported = 0;

        foreach ($rows as $assoc) {
            $idea = $this->mapRowToIdea($assoc);

            if ($idea === null) {
                continue;
            }

            $slug = Str::slug($idea['name']);
            $seededSlugs[] = $slug;
            $capitalEstimate = (int) ($idea['capital_estimate'] ?? 0);
            unset($idea['capital_estimate']);

            $existing = MicroBusinessIdea::query()
                ->where('slug', $slug)
                ->first();

            MicroBusinessIdea::updateOrCreate(
                ['slug' => $slug],
                [
                    ...$idea,
                    'description' => $idea['description'] !== ''
                        ? $idea['description']
                        : ($existing?->description ?? $this->buildDescription(
                            $idea['capital_min'],
                            $capitalEstimate,
                            (string) ($assoc['kategori'] ?? $assoc['lokasi'] ?? ''),
                            (string) ($assoc['waktu'] ?? '')
                        )),
                    'slug' => $slug,
                    'is_active' => true,
                ]
            );

            $imported++;
        }

        $deactivated = 0;

        if ($deactivateMissing && count($seededSlugs) > 0) {
            $deactivated = MicroBusinessIdea::query()
                ->whereNotIn('slug', $seededSlugs)
                ->update(['is_active' => false]);
        }

        return [
            'imported' => $imported,
            'deactivated' => $deactivated,
        ];
    }

    /**
     * @return array<int,array<string,string>>
     */
    private function rowsFromFile(string $path, ?string $extension): array
    {
        $extension = Str::lower($extension ?: pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'csv', 'txt' => $this->rowsFromCsv($path),
            'xlsx' => $this->rowsFromXlsx($path),
            default => throw new RuntimeException('Format file harus CSV atau Excel .xlsx.'),
        };
    }

    /**
     * @return array<int,array<string,string>>
     */
    private function rowsFromCsv(string $path): array
    {
        $file = new \SplFileObject($path);
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

        $header = null;
        $rows = [];

        foreach ($file as $row) {
            if (! is_array($row)) {
                continue;
            }

            $row = array_map(fn ($value) => $value === null ? '' : trim((string) $value), $row);

            if (count($row) === 1 && $row[0] === '') {
                continue;
            }

            if ($header === null) {
                $header = $this->normalizeHeader($row);
                continue;
            }

            $rows[] = $this->combineRow($header, $row);
        }

        return $rows;
    }

    /**
     * @return array<int,array<string,string>>
     */
    private function rowsFromXlsx(string $path): array
    {
        if (! class_exists(ZipArchive::class)) {
            throw new RuntimeException('Ekstensi PHP ZipArchive diperlukan untuk membaca file .xlsx.');
        }

        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException('File Excel tidak dapat dibuka.');
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($sheetXml === false) {
            throw new RuntimeException('Sheet pertama tidak ditemukan di file Excel.');
        }

        $sheet = simplexml_load_string($sheetXml);

        if ($sheet === false) {
            throw new RuntimeException('Sheet Excel tidak valid.');
        }

        $rawRows = [];

        foreach ($sheet->sheetData->row as $row) {
            $values = [];

            foreach ($row->c as $cell) {
                $reference = (string) $cell['r'];
                $index = $this->columnIndexFromReference($reference);
                $type = (string) $cell['t'];
                $value = '';

                if ($type === 's') {
                    $value = $sharedStrings[(int) $cell->v] ?? '';
                } elseif ($type === 'inlineStr') {
                    $value = trim((string) $cell->is->t);
                } else {
                    $value = trim((string) $cell->v);
                }

                $values[$index] = $value;
            }

            if (count($values) > 0) {
                ksort($values);
                $rawRows[] = $values;
            }
        }

        if (count($rawRows) === 0) {
            return [];
        }

        $header = $this->normalizeHeader(array_values($rawRows[0]));
        $rows = [];

        foreach (array_slice($rawRows, 1) as $row) {
            $rows[] = $this->combineRow($header, $row);
        }

        return $rows;
    }

    /**
     * @return array<int,string>
     */
    private function readSharedStrings(ZipArchive $zip): array
    {
        $xml = $zip->getFromName('xl/sharedStrings.xml');

        if ($xml === false) {
            return [];
        }

        $shared = simplexml_load_string($xml);

        if ($shared === false) {
            return [];
        }

        $values = [];

        foreach ($shared->si as $item) {
            $text = '';

            if (isset($item->t)) {
                $text = (string) $item->t;
            } else {
                foreach ($item->r as $run) {
                    $text .= (string) $run->t;
                }
            }

            $values[] = trim($text);
        }

        return $values;
    }

    private function columnIndexFromReference(string $reference): int
    {
        preg_match('/^[A-Z]+/i', $reference, $matches);
        $letters = strtoupper($matches[0] ?? 'A');
        $index = 0;

        foreach (str_split($letters) as $letter) {
            $index = ($index * 26) + (ord($letter) - 64);
        }

        return $index - 1;
    }

    /**
     * @param array<int,string> $row
     * @return array<int,string>
     */
    private function normalizeHeader(array $row): array
    {
        if (isset($row[0])) {
            $row[0] = ltrim($row[0], "\xEF\xBB\xBF");
        }

        return array_map(fn ($header) => Str::of($header)->lower()->replace(' ', '_')->value(), $row);
    }

    /**
     * @param array<int,string> $header
     * @param array<int,string> $row
     * @return array<string,string>
     */
    private function combineRow(array $header, array $row): array
    {
        $assoc = [];

        foreach ($header as $index => $key) {
            if ($key !== '') {
                $assoc[$key] = trim((string) ($row[$index] ?? ''));
            }
        }

        return $assoc;
    }

    /**
     * @param array<string,string> $assoc
     * @return array<string,mixed>|null
     */
    private function mapRowToIdea(array $assoc): ?array
    {
        $name = trim((string) ($assoc['namausaha'] ?? $assoc['nama_usaha'] ?? $assoc['name'] ?? ''));

        if ($name === '') {
            return null;
        }

        $modalEstimate = $this->moneyToInt((string) ($assoc['modal'] ?? '0'));
        $modalMinRaw = (string) ($assoc['modal_min'] ?? '');
        $modalMin = $modalMinRaw !== ''
            ? $this->moneyToInt($modalMinRaw)
            : $this->estimateModalMinFromScore($modalEstimate, (string) ($assoc['skormodal'] ?? ''));

        $categoryRaw = trim((string) ($assoc['kategori'] ?? $assoc['lokasi'] ?? ''));
        $timeRaw = trim((string) ($assoc['waktu'] ?? ''));
        [$freeTimeMin, $freeTimeMax] = $this->mapWaktuToRange($timeRaw);

        return [
            'name' => $name,
            'description' => trim((string) ($assoc['deskripsi'] ?? $assoc['description'] ?? '')),
            'capital_min' => max(0, $modalMin),
            'capital_estimate' => $modalEstimate,
            'capital_max' => null,
            'free_time_min_hours' => $freeTimeMin,
            'free_time_max_hours' => $freeTimeMax,
            'suitable_locations' => $this->mapCategoryToCodes($categoryRaw),
        ];
    }

    private function moneyToInt(string $value): int
    {
        return (int) preg_replace('/[^\d]/', '', $value);
    }

    private function mapCategoryToCodes(string $category): array
    {
        $category = Str::lower(trim($category));

        return match ($category) {
            'online' => ['online'],
            'rumah', 'rumahan' => ['rumahan'],
            'jalan', 'kios', 'offline' => ['offline'],
            'hybrid', 'fleksibel', 'online + offline', 'online offline' => ['hybrid'],
            default => ['hybrid'],
        };
    }

    private function mapWaktuToRange(string $waktu): array
    {
        $waktu = Str::lower(trim($waktu));

        return match ($waktu) {
            'rendah' => [2, 6],
            'sedang' => [7, 14],
            'tinggi' => [15, 30],
            default => [0, null],
        };
    }

    private function estimateModalMinFromScore(int $modalEstimate, string $score): int
    {
        if ($modalEstimate <= 0) {
            return 0;
        }

        $ratio = match (trim($score)) {
            '4' => 0.50,
            '3' => 0.65,
            '2' => 0.80,
            '1' => 0.90,
            default => 0.75,
        };

        $min = (int) round($modalEstimate * $ratio);
        $min = max(50_000, $min);
        $min = (int) (round($min / 50_000) * 50_000);

        return min($min, $modalEstimate);
    }

    private function buildDescription(int $modalMin, int $modalEstimate, string $category, string $time): string
    {
        $parts = [
            'Modal minimal: Rp' . number_format($modalMin, 0, ',', '.'),
        ];

        if ($modalEstimate > 0 && $modalEstimate !== $modalMin) {
            $parts[] = 'Estimasi modal: Rp' . number_format($modalEstimate, 0, ',', '.');
        }

        if (trim($category) !== '') {
            $parts[] = 'Kategori: ' . trim($category);
        }

        if (trim($time) !== '') {
            $parts[] = 'Waktu: ' . trim($time);
        }

        return implode('. ', $parts) . '.';
    }
}
