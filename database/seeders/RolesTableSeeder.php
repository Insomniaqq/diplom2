<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'Manager'],
            ['name' => 'Employee'],
        ];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }
    }
} 