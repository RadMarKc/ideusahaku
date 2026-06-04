<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible_as_first_page(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Login Pengguna')
            ->assertSee('Username')
            ->assertSee('Password');
    }

    public function test_user_can_login_with_username_and_password(): void
    {
        $user = User::factory()->create([
            'username' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $this->post(route('login.submit'), [
            'username' => 'admin',
            'password' => 'password',
        ])->assertRedirect(route('rekomendasi.form'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_recommendation_page_requires_authentication(): void
    {
        $this->get(route('rekomendasi.form'))
            ->assertRedirect(route('login'));
    }
}
