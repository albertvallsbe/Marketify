<?php
use Tests\TestCase;
use App\Models\User;
use App\Models\Shop;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test crear tienda
     */
    public function testCreateShopsTable() {
        $user = User::factory()->create();
        $shop = Shop::factory()->create(['user_id' => $user->id]);
        $this->assertNotNull($shop);
    }

    /**
     * Test crear shopname único
     */
    public function testShopnameUniqueness() {
        $shopname = 'example_shop';

        $user = User::factory()->create();

        Shop::factory()->create(['shopname' => $shopname, 'user_id' => $user->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Shop::factory()->create(['shopname' => $shopname, 'user_id' => $user->id]);
    }

    /**
     * Test crear username único
     */
    public function testUsernameUniqueness() {
        $username = 'example_username';

        $user = User::factory()->create();

        Shop::factory()->create(['username' => $username, 'user_id' => $user->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Shop::factory()->create(['username' => $username, 'user_id' => $user->id]);
    }

    /**
     * Test crear DNI único
     */
    public function testNifUniqueness() {
        $nif = 'ABCD1234';

        $user = User::factory()->create();

        Shop::factory()->create(['nif' => $nif, 'user_id' => $user->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Shop::factory()->create(['nif' => $nif, 'user_id' => $user->id]);
    }

    /**
     * Test crear y acceder a la URL de la tienda
     */
    public function testUrlUniqueness() {
        $url = 'example-url';

        $user = User::factory()->create();

        Shop::factory()->create(['url' => $url, 'user_id' => $user->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);
        Shop::factory()->create(['url' => $url, 'user_id' => $user->id]);
        
        $response = $this->get('/shop/' . $url);
        $response->assertStatus(200);
    }
}