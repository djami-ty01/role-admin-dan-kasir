<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('admin'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_is_forbidden_from_admin_page(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('admin'))
            ->assertForbidden();
    }

    public function test_admin_can_open_admin_page(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => 'admin'])->save();

        $this->actingAs($admin)
            ->get(route('admin'))
            ->assertOk()
            ->assertSee('Selamat Datang di Halaman Admin');
    }

    public function test_cashier_can_open_cashier_page_but_not_admin_page(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)
            ->get(route('kasir'))
            ->assertOk()
            ->assertSee('Selamat Datang, Kasir');

        $this->actingAs($cashier)
            ->get(route('admin'))
            ->assertForbidden();
    }
}
