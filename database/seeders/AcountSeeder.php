<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AcountSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'admin',
            ],
            [
                'name' => 'petugas',
                'email' => 'petugas@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'petugas',
            ],
            [
                'name' => 'owner',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'owner',
            ],
        ]);
    }
}
