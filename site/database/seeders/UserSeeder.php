<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
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
            'role'=>'customer',
        ]);
        User::factory()->create([
            'email'=> 'venedor@test.com',
            'name'=>'venedor',
            'password'=> Hash::make('12345678'),
            'role'=>'seller',
        ]);
        User::factory()->create([
            'email'=> 'venedor2@test.com',
            'name'=>'venedor2',
            'password'=> Hash::make('12345678'),
            'role'=>'seller',
        ]);
        User::factory()->create([
            'email'=> 'david@marketify.com',
            'name'=>'david',
            'password'=> Hash::make('12345678'),
            'role'=>'seller',
        ]);
        User::factory()->create([
            'email'=> 'oscar@marketify.com',
            'name'=>'oscar',
            'password'=> Hash::make('12345678'),
            'role'=>'seller',
        ]);
        User::factory()->create([
            'email'=> 'albert@marketify.com',
            'name'=>'albert',
            'password'=> Hash::make('12345678'),
            'role'=>'seller',
        ]);
        User::factory()
            ->count(10)
            ->create();
    }
}
