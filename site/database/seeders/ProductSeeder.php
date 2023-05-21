<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('products')->delete();
        $productCount = 10;
        $delay = 1000; // 1 segundo en milisegundos

        for ($i = 0; $i < $productCount; $i++) {
            Product::factory()->create();
            // Agrega un retraso de 1 segundo (1000 milisegundos) antes de crear el siguiente producto
            usleep($delay * 1000);
        }
    }
}
