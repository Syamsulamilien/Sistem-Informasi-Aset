<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pku.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'teknisi@pku.com'],
            [
                'name' => 'Teknisi IT',
                'username' => 'teknisi',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
            ]
        );

        User::updateOrCreate(
            ['email' => 'viewer@pku.com'],
            [
                'name' => 'Viewer',
                'username' => 'viewer',
                'password' => Hash::make('password'),
                'role' => 'viewer',
            ]
        );
    }
}
