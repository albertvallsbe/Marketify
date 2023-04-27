<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShopsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        DB::table('shops')->delete();
        $seller = User::where('role', 'seller')->first();
        Shop::factory()->state([
            'user_id' => $seller->id,
        ])->create([
            'shopname' => 'Awesome Shop!',
            'username' => 'Seller',
            'nif' => '0001A',
            'url' => Shop::generateURL('Awesome Shop!'),
        ]);
    }
}
