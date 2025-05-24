<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@mail.ru'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
            ]
        );
    }
} 