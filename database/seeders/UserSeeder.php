<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Ensure you have the correct namespace for your User model
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create the "owner" role
        $role = Role::updateOrCreate(['name' => 'owner', 'guard_name' => 'web']);

        // Fetch all permission IDs
        $permissions = Permission::pluck('id', 'id')->all();

        // Sync permissions with the role
        $role->syncPermissions($permissions);

        // Create an admin user and assign the role
        User::updateOrCreate(
            ['email' => 'ahmed@gmail.com'], // Unique field
            [
                'name' => 'Ahmed',
                'password' => Hash::make('12345678'),
                'roles_name' => ['owner'],
                'Status' => 'مفعل',
            ]
        )->assignRole($role);
    }
}
