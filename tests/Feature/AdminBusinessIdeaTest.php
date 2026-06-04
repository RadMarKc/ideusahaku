<?php

namespace Tests\Feature;

use App\Models\MicroBusinessIdea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminBusinessIdeaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_business_idea_dashboard(): void
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

        $this->actingAs(User::factory()->create())
            ->get(route('admin.business-ideas.index'))
            ->assertOk()
            ->assertSee('Dashboard Data Usaha')
            ->assertSee($idea->name)
            ->assertSee('Deskripsi awal.');
    }

    public function test_admin_can_import_csv_business_ideas(): void
    {
        $csv = implode("\n", [
            'namausaha,modal,skormodal,kategori,waktu,deskripsi',
            'Laundry Kiloan,1000000,3,offline,sedang,Layanan laundry rumahan.',
        ]);

        $file = UploadedFile::fake()->createWithContent('usaha.csv', $csv);

        $this->actingAs(User::factory()->create())
            ->post(route('admin.business-ideas.import'), [
                'business_file' => $file,
            ])
            ->assertRedirect(route('admin.business-ideas.index'));

        $this->assertDatabaseHas('micro_business_ideas', [
            'slug' => 'laundry-kiloan',
            'description' => 'Layanan laundry rumahan.',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_manual_description(): void
    {
        $idea = MicroBusinessIdea::query()->create([
            'name' => 'Katering Harian',
            'slug' => 'katering-harian',
            'description' => null,
            'capital_min' => 500000,
            'capital_max' => null,
            'free_time_min_hours' => 15,
            'free_time_max_hours' => 30,
            'suitable_locations' => ['rumahan'],
            'is_active' => true,
        ]);

        $this->actingAs(User::factory()->create())
            ->put(route('admin.business-ideas.update', $idea), [
                'description' => 'Cocok untuk ibu rumah tangga dengan jaringan pelanggan sekitar.',
            ])
            ->assertRedirect(route('admin.business-ideas.index', ['page' => 1]));

        $this->assertDatabaseHas('micro_business_ideas', [
            'slug' => 'katering-harian',
            'description' => 'Cocok untuk ibu rumah tangga dengan jaringan pelanggan sekitar.',
            'is_active' => false,
        ]);
    }
}
