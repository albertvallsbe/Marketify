<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => 'test@example.com',
            'current-password' => 'password123',
        ]);

        $response->assertRedirect(route('product.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function testFailedLogin()
    {
        $this->withoutExceptionHandling();

        $loginData = [
            'login' => $this->faker->email(),
            'current-password' => $this->faker->password(),
        ];

        $response = $this->post('/login', $loginData);

        $response->assertRedirect(route('login.index'))
            ->assertSessionHas('status', 'Incorrect username or password!');

        // Verifica que no se haya autenticado
        $this->assertFalse(Auth::check());

    }
}
