<?php

namespace Tests\Feature;

use App\Models\MicroBusinessIdea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MicroBusinessRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_limits_business_categories(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('rekomendasi.form'));

        $response
            ->assertOk()
            ->assertSee('Online')
            ->assertSee('Offline')
            ->assertSee('Rumahan')
            ->assertSee('Hybrid (Online + Offline)')
            ->assertDontSee('Perkotaan')
            ->assertDontSee('Pasar/Komersial');
    }

    public function test_recommendation_uses_weighted_product_method(): void
    {
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

        $expectedScore = number_format(round(pow(0.5, 0.45) * 100, 2), 2);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('rekomendasi.recommend'), [
            'capital' => 500,
            'location' => 'offline',
            'free_time_hours' => 10,
        ]);

        $response
            ->assertOk()
            ->assertSee('Weighted Product Method')
            ->assertSee('Usaha Modal Pas')
            ->assertSee(route('rekomendasi.detail', 'usaha-modal-pas'))
            ->assertSee($expectedScore . '%');
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
