<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Login Pengguna')
            ->assertSee('Username')
            ->assertSee('Password');
    }

    public function test_admin_can_login_with_username_and_password(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $this->post(route('login.submit'), [
            'username' => 'admin',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_recommendation_page_is_publicly_accessible(): void
    {
        $this->get(route('rekomendasi.form'))
            ->assertOk()
            ->assertSee('Temukan ide usaha mikro yang paling cocok');
    }

    public function test_admin_dashboard_requires_authentication(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }
}
