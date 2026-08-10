<?php

namespace Tests\Feature;

use App\Models\MicroBusinessIdea;
use App\Models\User;
use Database\Seeders\BusinessMasterOptionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MicroBusinessRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_limits_business_categories(): void
    {
        $this->seed(BusinessMasterOptionSeeder::class);

        $response = $this->actingAs(User::factory()->create())
            ->get(route('rekomendasi.form'));

        $response
            ->assertOk()
            ->assertSee('Online')
            ->assertSee('Offline')
            ->assertSee('Rumah')
            ->assertSee('Fleksibel')
            ->assertDontSee('Pasar/Komersial');
    }

    public function test_recommendation_uses_weighted_product_method(): void
    {
        $this->seed(BusinessMasterOptionSeeder::class);

        MicroBusinessIdea::query()->create([
            'name' => 'Usaha Modal Pas',
            'slug' => 'usaha-modal-pas',
            'description' => 'Cocok untuk modal uji.',
            'capital_min' => 1000,
            'capital_max' => null,
            'free_time_min_hours' => 10,
            'free_time_max_hours' => null,
            'suitable_locations' => ['offline'],
            'is_active' => true,
        ]);

        $response = $this->post(route('rekomendasi.recommend'), [
            'capital' => 500,
            'location' => 'offline',
            'time' => 'fleksibel',
        ]);

        $response
            ->assertRedirect(route('rekomendasi.form', ['page' => 1]));

        $this->get(route('rekomendasi.form', ['page' => 1]))
            ->assertOk()
            ->assertSee('Weighted Product Method')
            ->assertSee('Usaha Modal Pas')
            ->assertSee(route('rekomendasi.detail', 'usaha-modal-pas'));
    }

    public function test_recommendation_results_are_paginated_five_per_page(): void
    {
        $this->seed(BusinessMasterOptionSeeder::class);

        for ($i = 1; $i <= 12; $i++) {
            MicroBusinessIdea::query()->create([
                'name' => "Usaha Uji $i",
                'slug' => "usaha-uji-$i",
                'description' => "Deskripsi $i.",
                'capital_min' => 1000,
                'capital_max' => null,
                'free_time_min_hours' => 10,
                'free_time_max_hours' => null,
                'suitable_locations' => ['offline'],
                'location_label' => 'Offline',
                'time_label' => 'Fleksibel',
                'is_active' => true,
            ]);
        }

        $this->post(route('rekomendasi.recommend'), [
            'capital' => 500,
            'location' => 'offline',
            'time' => 'fleksibel',
        ]);

        $page1 = $this->get(route('rekomendasi.form', ['page' => 1]))->assertOk();

        $count = preg_match_all('/data-bs-target="#detail-\d+"/', $page1->getContent());

        $this->assertSame(5, $count);

        $page2 = $this->get(route('rekomendasi.form', ['page' => 2]))->assertOk();

        $countPage2 = preg_match_all('/data-bs-target="#detail-\d+"/', $page2->getContent());

        $this->assertGreaterThan(0, $countPage2);
    }

    public function test_user_can_open_business_idea_detail_from_active_idea(): void
    {
        $idea = MicroBusinessIdea::query()->create([
            'name' => 'Warung Kopi Kecil',
            'slug' => 'warung-kopi-kecil',
            'description' => 'Informasi lengkap usaha warung kopi.',
            'capital_min' => 750000,
            'capital_max' => 1500000,
            'free_time_min_hours' => 15,
            'free_time_max_hours' => 30,
            'suitable_locations' => ['offline'],
            'is_active' => true,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('rekomendasi.detail', $idea))
            ->assertOk()
            ->assertSee('Warung Kopi Kecil')
            ->assertSee('Informasi lengkap usaha warung kopi.')
            ->assertSee('Rp750.000')
            ->assertSee('Offline');
    }

    public function test_inactive_business_idea_detail_is_hidden(): void
    {
        $idea = MicroBusinessIdea::query()->create([
            'name' => 'Usaha Nonaktif',
            'slug' => 'usaha-nonaktif',
            'description' => 'Tidak ditampilkan.',
            'capital_min' => 100000,
            'capital_max' => null,
            'free_time_min_hours' => 2,
            'free_time_max_hours' => 6,
            'suitable_locations' => ['online'],
            'is_active' => false,
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('rekomendasi.detail', $idea))
            ->assertNotFound();
    }
}
