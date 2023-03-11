<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Category_Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class Category_Product_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category_product')->delete();
        $products = Product::all();

        foreach ($products as $product) {
          $numberCategories = Category::count();
          $randomCategories = rand(1,$numberCategories);

          $product->category()->attach(category::find($randomCategories));
      }
    }
}
