<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Seed the roles table.
     *
     * @return void
     */
    public function run()
    {
        // List of roles to create
        $roles = [
            'admin',
            'owner',
        ];

        // Create each role
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
