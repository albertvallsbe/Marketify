<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_see_register_view_when_no_authenticated() {
        $response = $this->get(route('register.index'));
        $response->assertStatus(200);
    }

    public function test_redirect_register_view_when_authenticated() {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('register.index'));
        $response->assertStatus(302);
    }

    public function test_register_user()
    {
        $userData = [
            'name' => 'Pou',
            'email' => 'pou@example.com',
            'password' => 'password',
        ];
        
        $response = $this->post(route('auth.register'), $userData);
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    }

    public function test_register_error_when_are_users_with_same_data()
    {
        $userData = [
            'name' => 'Pou',
            'email' => 'pou@example.com',
            'password' => 'password',
        ];
        
        $response = $this->post(route('auth.register'), $userData);
        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
        
        $response = $this->post(route('auth.register'), $userData);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_validation_errors()
    {
        $userData = [
            'name' => '',
            'email' => 'loremipsumdolorsitametconsecteturadipiscingelitloremipsumdolorsitametconsecteturadipiscingelit',
            'password' => 'password',
        ];
        
        $response = $this->post(route('auth.register'), $userData);
        $this->assertDatabaseMissing('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);
    }
}
