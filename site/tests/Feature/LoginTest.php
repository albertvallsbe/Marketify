<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_see_login_view_when_no_authenticated() {
        $response = $this->get(route('login.index'));
        $response->assertStatus(200);
    }

    public function test_redirect_login_view_when_authenticated() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('login.index'));
        $response->assertStatus(302);
    }

    public function test_successful_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('auth.login'), [
            'login' => 'test@example.com',
            'current-password' => 'password123',
        ]);

        $response->assertRedirect(route('product.index'));
        $this->assertTrue(Auth::check());
    }

    public function test_failed_Login()
    {
        $this->withoutExceptionHandling();

        $loginData = [
            'login' => 'test@example.com',
            'current-password' => 'password123',
        ];

        $response = $this->post(route('auth.login'), $loginData);

        $response->assertRedirect(route('login.index'))
            ->assertSessionHas('status', 'Incorrect username or password!');

        $this->assertFalse(Auth::check());

    }
}
