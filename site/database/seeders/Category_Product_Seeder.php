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
          // $numberCategories = Category::count();
          // $randomCategories = rand(0,$numberCategories);

           $randomCategories = rand(0,10);

          //$product = new Product();
          // $product->category()->attach(
          // [
          //   ($product->id),
          //    ($randomCategories)
          // ]);
           $product->category()->attach(category::find($randomCategories));
      }
    }
}
