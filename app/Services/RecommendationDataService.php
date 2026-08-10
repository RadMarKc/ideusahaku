<?php

namespace App\Services;

use App\Models\BusinessMasterOption;
use App\Models\Criterion;
use App\Models\FormulaSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class RecommendationDataService
{
    private const TTL = 3600;

    private const KEY_LOCATIONS = 'rekomendasi.locations';

    private const KEY_TIMES = 'rekomendasi.times';

    private const KEY_CRITERIA = 'rekomendasi.criteria';

    private const KEY_FORMULA = 'rekomendasi.formula';

    public function locations(): Collection
    {
        return $this->remember(self::KEY_LOCATIONS, function () {
            return $this->masterOptions(BusinessMasterOption::TYPE_LOCATION);
        });
    }

    public function times(): Collection
    {
        return $this->remember(self::KEY_TIMES, function () {
            return $this->masterOptions(BusinessMasterOption::TYPE_TIME);
        });
    }

    public function criteria(): Collection
    {
        return $this->remember(self::KEY_CRITERIA, function () {
            return Criterion::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(['id', 'code', 'name', 'weight', 'type', 'sort_order']);
        });
    }

    public function formula(): FormulaSetting
    {
        return $this->remember(self::KEY_FORMULA, function () {
            return FormulaSetting::current();
        });
    }

    /**
     * @return Collection<int, BusinessMasterOption>
     */
    private function masterOptions(string $type): Collection
    {
        return BusinessMasterOption::query()
            ->active()
            ->ofType($type)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->keyBy('code');
    }

    public function flush(): void
    {
        Cache::forget(self::KEY_LOCATIONS);
        Cache::forget(self::KEY_TIMES);
        Cache::forget(self::KEY_CRITERIA);
        Cache::forget(self::KEY_FORMULA);
    }

    private function remember(string $key, callable $callback): mixed
    {
        return Cache::remember($key, self::TTL, $callback);
    }
}
