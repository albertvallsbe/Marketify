<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email'=> 'comprador@test.com',
            'name'=>'comprador',
            'password'=>Hash::make('12345678'),
            
        ]);
        User::factory()->create([
            'email'=> 'venedor@test.com',
            'name'=>'venedor',
            'password'=>Hash::make('12345678'),
        ]);
    }
}
