<?php

namespace Tests\Unit\Models;

use App\Models\Product;
use App\Models\Category;
use Tests\TestCase;
// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class CategoryProductTest extends TestCase
{
    use  WithFaker, RefreshDatabase;

    public function test_category_can_have_products()
    {
        $category = Category::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $category->product()->attach([$product1->id, $product2->id]);

        $this->assertTrue($category->product->contains($product1));
        $this->assertTrue($category->product->contains($product2));
    }

    public function test_product_can_have_categories()
    {
        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();
        $product = Product::factory()->create();

        $product->category()->attach([$category1->id, $category2->id]);

        $this->assertTrue($product->category->contains($category1));
        $this->assertTrue($product->category->contains($category2));
    }
}
