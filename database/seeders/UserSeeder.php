<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pku.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Teknisi IT',
            'email' => 'teknisi@pku.com',
            'password' => Hash::make('password'),
            'role' => 'teknisi',
        ]);

        User::create([
            'name' => 'Viewer',
            'email' => 'viewer@pku.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
        ]);
    }
}
