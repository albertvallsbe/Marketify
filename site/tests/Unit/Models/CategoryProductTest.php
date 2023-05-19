<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\Shop;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryProductTest extends TestCase
{
    use  WithFaker, RefreshDatabase;

    public function test_category_can_have_products()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id
        ]);
        $category = Category::factory()->create();
        $product1 = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);
        $product2 = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);

        $category->product()->attach([$product1->id, $product2->id]);

        $this->assertTrue($category->product->contains($product1));
        $this->assertTrue($category->product->contains($product2));
    }

    public function test_product_can_have_categories()
    {
        $user = User::factory()->create();
        $shop = Shop::factory()->create([
            'user_id' => $user->id
        ]);
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
        ]);
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $product->category()->attach([$category1->id, $category2->id]);

        $this->assertTrue($product->category->contains($category1));
        $this->assertTrue($product->category->contains($category2));
    }
}
