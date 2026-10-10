<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_admin_login_and_dashboard_open_management(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->withSession(['url.intended' => url('/movies')])->post('/login', [
            'email' => $admin->email, 'password' => 'password',
        ])->assertRedirect(route('admin.movies'));
        $this->assertAuthenticatedAs($admin);
        $this->get('/dashboard')->assertRedirect(route('admin.movies'));
        $this->get(route('admin.movies'))->assertOk();
    }

    public function test_member_login_preserves_the_requested_page(): void
    {
        $member = User::factory()->create(['is_admin' => false]);
        $this->withSession(['url.intended' => url('/booking-history')])->post('/login', [
            'email' => $member->email, 'password' => 'password',
        ])->assertRedirect(url('/booking-history'));
        $this->get(route('admin.movies'))->assertForbidden();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
