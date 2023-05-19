<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        DB::table('shops')->delete();
        $seller = User::where('id', '2')->first();
        $seller2 = User::where('id', '3')->first();
        $sellerdavid = User::where('id', '4')->first();
        $selleroscar = User::where('id', '5')->first();
        $selleralbert = User::where('id', '6')->first();
        Shop::factory()->state([
            'user_id' => $seller->id,
        ])->create([
            'shopname' => 'Awesome Shop!',
            'username' => 'Awesome Seller',
            'nif' => '12345678A',
            'url' => Shop::generateURL('Awesome Shop!'),
        ]);
        Shop::factory()->state([
            'user_id' => $seller2->id,
        ])->create([
            'shopname' => 'Amazing Shop!',
            'username' => 'Amazing Seller',
            'nif' => '87654321B',
            'url' => Shop::generateURL('Amazing Shop!'),
        ]);
        Shop::factory()->state([
            'user_id' => $sellerdavid->id,
        ])->create([
            'shopname' => 'David Shop!',
            'username' => 'David Seller',
            'nif' => '23654321D',
            'url' => Shop::generateURL('David Shop!'),
        ]);
        Shop::factory()->state([
            'user_id' => $selleroscar->id,
        ])->create([
            'shopname' => 'Oscar Shop!',
            'username' => 'Oscar Seller',
            'nif' => '22654321O',
            'url' => Shop::generateURL('Oscar Shop!'),
        ]);
        Shop::factory()->state([
            'user_id' => $selleralbert->id,
        ])->create([
            'shopname' => 'Titu Shop!',
            'username' => 'Albert Seller',
            'nif' => '33654321A',
            'url' => Shop::generateURL('Titu Shop!'),
        ]);
    }
}
