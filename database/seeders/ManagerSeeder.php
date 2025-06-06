<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Manager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Manager
        $adminUser = User::create([
            'password' => bcrypt('Manager@123'),
        ]);
        // $role=Role::where('id',1)->first();
        // $adminUser->assignRole($role->name);

                // Assign role with api guard
        $adminUser->assignRole(Role::findByName('Manager', 'api'));



        $adminManager = Manager::create([
            'user_id' => $adminUser->id,
            'email' => 'Manager@ajyal.com',
        ]);

        // Create Secretariat
        $managerUser = User::create([
            'password' => bcrypt('Secretariat@123'),
        ]);
        // $managerUser->assignRole('Secretariat');
         // Assign role with api guard
        $managerUser->assignRole(Role::findByName('Secretariat', 'api'));


        $manager = Manager::create([
            'user_id' => $managerUser->id,
            'email' => 'Secretariat@ajyal.com',
        ]);
    }
}
