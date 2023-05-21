<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

class UserTest extends TestCase {
    use RefreshDatabase;

    /**
     * Test crear usuario
     */
    public function testCreateUser() {
        $user = User::factory()->create();
        $this->assertNotNull($user);
    }

    /**
     * Test formato de correo inválido
     */
    public function testInvalidEmailFormat() {
        $email = 'invalid_email_format';
    
        $user = User::factory()->make(['email' => $email]);
        
        $validator = Validator::make($user->toArray(), [
            'email' => 'email'
        ]);
        
        $this->assertTrue($validator->fails());
    }

    
    /**
     * Test crear correo único
     */
    public function testEmailUniqueness() {
        $email = 'test@example.com';

        User::factory()->create(['email' => $email]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => $email]);
    }

    /**
     * Test crear username único
     */
    public function testUsernameUniqueness() {
        $name = 'testuser';

        User::factory()->create(['name' => $name]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['name' => $name]);
    }

    /**
     * Test longitud máxima
     */
    public function testMaxLengthValidation() {
        $user = User::factory()->make([
            'name' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
            'email' => 'loremipsumdolorsitametconsecteturadipiscingelitloremipsumdolorsitametconsecteturadipiscingelit@example.com',
            'password' => '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ]);

        $rules = [
            'name' => ['max:25'],
            'email' => ['max:25'],
            'password' => ['max:25']
        ];

        $validator = Validator::make($user->toArray(), $rules);

        $this->assertTrue($validator->fails());
        $this->assertCount(2, $validator->errors());
    }
}
