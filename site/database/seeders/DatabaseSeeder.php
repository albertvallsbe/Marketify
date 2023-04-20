<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(CategorySeeder::class);
       $this->call(ProductSeeder::class);
       $this->call(Category_Product_Seeder::class);
       $this->call(UsersTableSeeder::class);
       $this->call(ShopsTableSeeder::class);
    }
}
