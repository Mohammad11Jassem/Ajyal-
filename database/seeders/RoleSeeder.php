<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Manager Role
        $adminRole = Role::create(['name' => 'Manager', 'guard_name' => 'api']);
        $adminRole->givePermissionTo(Permission::all());

        // Create Secretariat Role
        $managerRole = Role::create(['name' => 'Secretariat', 'guard_name' => 'api']);
        $managerRole->givePermissionTo(permissions: [
            'view users', 'create users', 'edit users',
            // 'view teachers', 'create teachers', 'edit teachers',
            'view students', 'create students', 'edit students',
            'view parents', 'create parents', 'edit parents',
            'view courses', 'create courses', 'edit courses',
            'view attendance', 'mark attendance', 'edit attendance',
            'view grades', 'view reports', 'generate reports',
            'view settings'
        ]);

        // Create Teacher Role
          $teacherRole = Role::create(['name' => 'Teacher', 'guard_name' => 'api']);
        $teacherRole->givePermissionTo([
            'view students', 'view parents',
            'view courses', 'view attendance', 'mark attendance',
            'view grades', 'create grades', 'edit grades',
            'view reports', 'generate reports'
        ]);

        // Create Student Role
       $studentRole = Role::create(['name' => 'Student', 'guard_name' => 'api']);
        $studentRole->givePermissionTo([
            'view courses',
            'view attendance',
            'view grades',
            'view reports'
        ]);

        // Create Parent Role
         $parentRole = Role::create(['name' => 'Parent', 'guard_name' => 'api']);
        $parentRole->givePermissionTo([
            'view attendance',
            'view grades',
            'view reports'
        ]);
    }
}
