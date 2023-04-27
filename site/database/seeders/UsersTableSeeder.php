<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->delete();
        User::factory()->create([
            'email'=> 'comprador@test.com',
            'name'=>'comprador',
            'password'=> Hash::make('12345678'),
            'role'=>'shopper',
        ]);
        User::factory()->create([
            'email'=> 'venedor@test.com',
            'name'=>'venedor',
            'password'=> Hash::make('12345678'),
            'role'=>'seller',
        ]);
    }
}
