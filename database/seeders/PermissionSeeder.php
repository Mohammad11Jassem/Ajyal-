<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // User Management Permissions
        Permission::create(['name' => 'view users', 'guard_name' => 'api']);
        Permission::create(['name' => 'create users', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit users', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete users', 'guard_name' => 'api']);

        // Role Management Permissions
        Permission::create(['name' => 'view roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'create roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete roles', 'guard_name' => 'api']);

        // Teacher Management Permissions
        Permission::create(['name' => 'view teachers', 'guard_name' => 'api']);
        Permission::create(['name' => 'create teachers', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit teachers', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete teachers', 'guard_name' => 'api']);

        // Student Management Permissions
        Permission::create(['name' => 'view students', 'guard_name' => 'api']);
        Permission::create(['name' => 'create students', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit students', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete students', 'guard_name' => 'api']);

        // Parent Management Permissions
        Permission::create(['name' => 'view parents', 'guard_name' => 'api']);
        Permission::create(['name' => 'create parents', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit parents', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete parents', 'guard_name' => 'api']);

        // Course Management Permissions
        Permission::create(['name' => 'view courses', 'guard_name' => 'api']);
        Permission::create(['name' => 'create courses', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit courses', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete courses', 'guard_name' => 'api']);

        // Attendance Management Permissions
        Permission::create(['name' => 'view attendance', 'guard_name' => 'api']);
        Permission::create(['name' => 'mark attendance', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit attendance', 'guard_name' => 'api']);

        // Grade Management Permissions
        Permission::create(['name' => 'view grades', 'guard_name' => 'api']);
        Permission::create(['name' => 'create grades', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit grades', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete grades', 'guard_name' => 'api']);

        // Report Management Permissions
        Permission::create(['name' => 'view reports', 'guard_name' => 'api']);
        Permission::create(['name' => 'generate reports', 'guard_name' => 'api']);
        Permission::create(['name' => 'export reports', 'guard_name' => 'api']);

        // Settings Management Permissions
        Permission::create(['name' => 'view settings', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit settings', 'guard_name' => 'api']);
    }
}
