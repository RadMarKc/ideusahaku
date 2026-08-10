<?php

namespace Tests\Feature;

use App\Models\MicroBusinessIdea;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessSeparationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_public_recommendation_form(): void
    {
        $this->get(route('rekomendasi.form'))
            ->assertOk();
    }

    public function test_guest_can_submit_recommendation(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->post(route('rekomendasi.recommend'), [
            'capital' => 1000000,
            'location' => 'offline',
            'time' => 'rendah',
        ])->assertRedirect(route('rekomendasi.form', ['page' => 1]));
    }

    public function test_guest_can_access_public_detail_page(): void
    {
        $idea = MicroBusinessIdea::query()->create([
            'name' => 'Jasa Cuci Sepatu',
            'slug' => 'jasa-cuci-sepatu',
            'description' => 'Deskripsi awal.',
            'capital_min' => 250000,
            'capital_max' => null,
            'free_time_min_hours' => 7,
            'free_time_max_hours' => 14,
            'suitable_locations' => ['offline'],
            'is_active' => true,
        ]);

        $this->get(route('rekomendasi.detail', $idea))
            ->assertOk()
            ->assertSee('Jasa Cuci Sepatu');
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_admin_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.business-ideas.index'))
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_pages(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.business-ideas.index'))
            ->assertOk();
    }
}
