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

        $this->actingAs(User::factory()->admin()->create())
            ->get(route('admin.business-ideas.index'))
            ->assertOk()
            ->assertSee('Dashboard Data Usaha')
            ->assertSee($idea->name)
            ->assertSee('Deskripsi awal.');
    }

    public function test_admin_can_import_csv_business_ideas(): void
    {
        $csv = implode("\n", [
            'id,namausaha,modal,modal_min,lokasi,waktu,skormodal,skorlokasi,skorwaktu,total_skor',
            '1,Reseller Baju,500000,250000,Online,Fleksibel,4,4,4,12',
        ]);

        $file = UploadedFile::fake()->createWithContent('usaha.csv', $csv);

        $this->actingAs(User::factory()->admin()->create())
            ->post(route('admin.business-ideas.import'), [
                'business_file' => $file,
            ])
            ->assertRedirect(route('admin.business-ideas.index'));

        $this->assertDatabaseHas('micro_business_ideas', [
            'slug' => 'reseller-baju',
            'capital_estimate' => 500000,
            'capital_min' => 250000,
            'location_label' => 'Online',
            'time_label' => 'Fleksibel',
            'is_active' => true,
        ]);

        $idea = MicroBusinessIdea::query()->where('slug', 'reseller-baju')->firstOrFail();

        $this->assertSame(4, $idea->capital_score);
        $this->assertSame(4, $idea->location_score);
        $this->assertSame(4, $idea->time_score);
        $this->assertSame(12, $idea->total_score);
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

        $this->actingAs(User::factory()->admin()->create())
            ->put(route('admin.business-ideas.update', $idea), [
                'name' => 'Katering Harian',
                'capital_estimate' => 500000,
                'capital_min' => 500000,
                'location_label' => 'Rumah',
                'time_label' => 'Tinggi',
                'capital_score' => 3,
                'location_score' => 2,
                'time_score' => 3,
                'total_score' => 8,
                'description' => 'Cocok untuk ibu rumah tangga dengan jaringan pelanggan sekitar.',
            ])
            ->assertRedirect(route('admin.business-ideas.index', ['page' => 1]));

        $this->assertDatabaseHas('micro_business_ideas', [
            'slug' => 'katering-harian',
            'description' => 'Cocok untuk ibu rumah tangga dengan jaringan pelanggan sekitar.',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_business_idea(): void
    {
        $idea = MicroBusinessIdea::query()->create([
            'name' => 'Pulsa & Token',
            'slug' => 'pulsa-token',
            'description' => null,
            'capital_min' => 650000,
            'capital_estimate' => 1000000,
            'capital_max' => null,
            'free_time_min_hours' => 2,
            'free_time_max_hours' => 6,
            'suitable_locations' => ['hybrid'],
            'location_label' => 'Fleksibel',
            'time_label' => 'Rendah',
            'capital_score' => 3,
            'location_score' => 3,
            'time_score' => 1,
            'total_score' => 7,
            'is_active' => true,
        ]);

        $this->actingAs(User::factory()->admin()->create())
            ->delete(route('admin.business-ideas.destroy', $idea))
            ->assertRedirect(route('admin.business-ideas.index', ['page' => 1]));

        $this->assertDatabaseMissing('micro_business_ideas', [
            'id' => $idea->id,
        ]);
    }
}
