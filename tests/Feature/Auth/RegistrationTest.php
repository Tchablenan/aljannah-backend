<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_is_closed_by_default(): void
    {
        $this->get('/register')->assertNotFound();

        $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();

        $this->assertGuest();
    }

    public function test_registration_screen_can_be_rendered_when_enabled(): void
    {
        config(['auth.allow_registration' => true]);

        $this->get('/register')->assertStatus(200);
    }

    public function test_new_users_can_register_when_enabled_but_are_not_admins(): void
    {
        config(['auth.allow_registration' => true]);

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
        $this->assertFalse((bool) User::where('email', 'test@example.com')->value('is_admin'));
    }
}
