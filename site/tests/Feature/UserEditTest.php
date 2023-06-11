<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class UserEditTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_redirect_user_edit_view_when_no_authenticated() {
        $response = $this->get(route('user.edit'));
        $response->assertStatus(302);
    }

    public function test_user_can_change_password() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.edit'));
        $response->assertStatus(200);

        $response = $this->post(route('user.changeData'), [
            'current-password' => $user->password,
            'new-password' => '12345678',
            'repeat-password' => '12345678'
        ]);
        $response->assertStatus(302);
    }
    
    public function test_user_can_change_avatar() {
        $user = User::factory()->create();
        $this->actingAs($user);

        Storage::fake('public');
        $image = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->get(route('user.edit'));
        $response->assertStatus(200);

        $response = $this->post(route('user.changeData'), [
            'avatar' => $image,
        ]);
        $response->assertStatus(302);
    }
        
    public function test_user_can_change_username() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('user.edit'));
        $response->assertStatus(200);

        $response = $this->post(route('user.changeData'), [
            'name' => 'PerePou',
        ]);
        $response->assertStatus(302);
    }

}
