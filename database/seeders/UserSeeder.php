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
            ['username' => 'admin'],
            [
                'name' => 'Super Administrator',
                'username' => 'admin',
                'email' => 'admin@smkbinatama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
            ]
        );

        User::updateOrCreate(
            ['username' => 'teknisi'],
            [
                'name' => 'Teknisi IT',
                'username' => 'teknisi',
                'email' => 'teknisi@smkbinatama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'teknisi',
            ]
        );

        User::updateOrCreate(
            ['username' => 'viewer'],
            [
                'name' => 'Viewer',
                'username' => 'viewer',
                'email' => 'viewer@smkbinatama.sch.id',
                'password' => Hash::make('password'),
                'role' => 'viewer',
            ]
        );
    }
}
