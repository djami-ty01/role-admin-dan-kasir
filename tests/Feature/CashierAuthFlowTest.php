<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierAuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_links_to_registration(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertSee(route('register'));
    }

    public function test_registration_always_creates_a_cashier_even_if_admin_role_is_submitted(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'New Cashier',
            'email' => 'cashier@example.com',
            'password' => 'StrongPassword123!',
            'password_confirmation' => 'StrongPassword123!',
            'role' => 'admin',
        ]);

        $user = User::where('email', 'cashier@example.com')->firstOrFail();

        $this->assertSame('kasir', $user->role);
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_redirects_admin_to_admin_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->post(route('login.process'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertRedirect(route('admin'));
    }

    public function test_login_redirects_cashier_to_cashier_page(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->post(route('login.process'), [
            'email' => $cashier->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->get(route('dashboard'))
            ->assertRedirect(route('kasir'));
    }
}
