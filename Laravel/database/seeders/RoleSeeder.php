<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'permissions' => json_encode(['*']),
            ],
            [
                'name' => 'Worker',
                'slug' => 'worker',
                'permissions' => json_encode(['manage-exhibits', 'manage-exibitions']),
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'permissions' => json_encode(['view-tickets']),
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
