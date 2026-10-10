<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CinemaPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_and_member_pages_render(): void
    {
        $this->seed(DatabaseSeeder::class);
        foreach (['/login', '/register', '/forgot-password'] as $path) $this->get($path)->assertOk();

        $this->actingAs(User::factory()->create());
        foreach (['/dashboard', '/movies', '/movies/1/showtimes', '/showtimes/1/seats', '/booking-history', '/profile'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_admin_pages_render_and_members_are_denied(): void
    {
        $this->seed(DatabaseSeeder::class);
        $member = User::factory()->create();
        $this->actingAs($member)->get('/admin/movies')->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);
        foreach (['/admin/movies', '/admin/showtimes', '/admin/theaters', '/admin/theaters/1/edit', '/admin/bookings', '/admin/users'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_an_existing_account_can_be_promoted_to_admin(): void
    {
        $user = User::factory()->create();
        $this->artisan('cinema:make-admin', ['email' => $user->email])->assertSuccessful();
        $this->assertTrue($user->fresh()->is_admin);
    }
}

