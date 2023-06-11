<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use GuzzleHttp\Client;
use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class APITest extends TestCase
{
    use RefreshDatabase;

    public function test_get_all_images_of_all_products()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $client = new Client();
        $response = $client->get(env('API_IP').'api/images/view/all', [
            'verify' => false,
                    'headers' => [
                        'Authorization' => env('API_TOKEN'),
                    ],
        ]);
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }

    public function test_get_all_images_of_a_product()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $products = $this->createProducts($shop);

        $client = new Client();
        $response = $client->get(env('API_IP').'api/images/view/'.$products[0]->id, [
            'verify' => false,
                    'headers' => [
                        'Authorization' => env('API_TOKEN'),
                    ],
        ]);
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }

    public function test_upload_image_of_a_product_by_seeder()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $client = new Client();
        $response = $client->get(env('API_IP').'api/images/view/'.$product->id, [
            'verify' => false,
                    'headers' => [
                        'Authorization' => env('API_TOKEN'),
                    ],
        ]);
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }

    public function test_upload_image_of_a_product()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $this->actingAs($seller);
        $createdProduct = Product::factory()->create(['shop_id' => $shop->id]);
        
        Storage::fake('public');
        $image = UploadedFile::fake()->image('test-image.jpg');
        $client = new Client();
    
        $name = uniqid('product_') . '.' . $image->getClientOriginalExtension();
        $path = "images/products/";
        $finalPath = $path . $name;
    
        $response = $client->post(env('API_IP') . 'api/images/insert', [
            'verify' => false,
            'headers' => [
                'Authorization' => env('API_TOKEN'),
            ],
            'multipart' => [
                [
                    'name' => 'name',
                    'contents' => $name,
                ],
                [
                    'name' => 'path',
                    'contents' => $finalPath,
                ],
                [
                    'name' => 'product_image',
                    'contents' => fopen($image->getPathname(), 'r'),
                    'filename' => $name,
                ],
                [
                    'name' => 'product_id',
                    'contents' => $createdProduct->id,
                ],
                [
                    'name' => 'main',
                    'contents' => true,
                ],
            ],
        ]);
    
        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }

    public function test_delete_image_of_a_product()
    {
        $seller = $this->createSeller();
        $shop = $this->createShop($seller);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $client = new Client();
        $response = $client->delete(env('API_IP').'api/images/delete/product/'.$product->id, [
            'verify' => false,
            'headers' => [
                'Authorization' => env('API_TOKEN'),
            ],
        ]);

        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }

    // ************* \\

    public static function createSeller()
    {   
        $seller = User::factory()->create(['role' => 'seller']);
        return $seller;
    }

    public static function createShop($seller)
    {   
        $url = uniqid();
        $shop = Shop::factory()->create([
            'user_id' => $seller->id,
            'url' => $url,
        ]);
        return $shop;
    }

    public static function createProducts($shop)
    {
        $products = Product::factory(2)->create(['shop_id' => $shop->id]);
        return $products;
    }
}
