<?php

namespace Database\Seeders;

use App\Models\BusinessMasterOption;
use Illuminate\Database\Seeder;

class BusinessMasterOptionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['type' => BusinessMasterOption::TYPE_CAPITAL, 'code' => 'modal_0_500k', 'label' => 'Rp0 - Rp500.000', 'score' => 4, 'value_min' => 0, 'value_max' => 500_000, 'sort_order' => 1],
            ['type' => BusinessMasterOption::TYPE_CAPITAL, 'code' => 'modal_500k_1500k', 'label' => 'Rp500.001 - Rp1.500.000', 'score' => 3, 'value_min' => 500_001, 'value_max' => 1_500_000, 'sort_order' => 2],
            ['type' => BusinessMasterOption::TYPE_CAPITAL, 'code' => 'modal_1500k_3000k', 'label' => 'Rp1.500.001 - Rp3.000.000', 'score' => 2, 'value_min' => 1_500_001, 'value_max' => 3_000_000, 'sort_order' => 3],
            ['type' => BusinessMasterOption::TYPE_CAPITAL, 'code' => 'modal_above_3000k', 'label' => 'Di atas Rp3.000.000', 'score' => 1, 'value_min' => 3_000_001, 'value_max' => null, 'sort_order' => 4],

            ['type' => BusinessMasterOption::TYPE_LOCATION, 'code' => 'online', 'label' => 'Online', 'score' => 4, 'sort_order' => 1],
            ['type' => BusinessMasterOption::TYPE_LOCATION, 'code' => 'rumah', 'label' => 'Rumah', 'score' => 2, 'sort_order' => 2],
            ['type' => BusinessMasterOption::TYPE_LOCATION, 'code' => 'offline', 'label' => 'Offline', 'score' => 3, 'sort_order' => 3],
            ['type' => BusinessMasterOption::TYPE_LOCATION, 'code' => 'fleksibel', 'label' => 'Fleksibel', 'score' => 4, 'sort_order' => 4],

            ['type' => BusinessMasterOption::TYPE_TIME, 'code' => 'rendah', 'label' => 'Rendah', 'score' => 1, 'sort_order' => 1],
            ['type' => BusinessMasterOption::TYPE_TIME, 'code' => 'sedang', 'label' => 'Sedang', 'score' => 2, 'sort_order' => 2],
            ['type' => BusinessMasterOption::TYPE_TIME, 'code' => 'tinggi', 'label' => 'Tinggi', 'score' => 3, 'sort_order' => 3],
            ['type' => BusinessMasterOption::TYPE_TIME, 'code' => 'fleksibel', 'label' => 'Fleksibel', 'score' => 4, 'sort_order' => 4],
        ];

        foreach ($rows as $row) {
            BusinessMasterOption::updateOrCreate(
                [
                    'type' => $row['type'],
                    'code' => $row['code'],
                ],
                [
                    'label' => $row['label'],
                    'score' => $row['score'],
                    'value_min' => $row['value_min'] ?? null,
                    'value_max' => $row['value_max'] ?? null,
                    'sort_order' => $row['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
