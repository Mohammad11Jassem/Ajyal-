<?php

namespace Database\Seeders;

use App\Models\ParentModel;
use App\Models\ParentStudent;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ParentModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $user = User::create([
            'password'=>123456789
        ]);

         $user->assignRole(Role::findByName('Parent', 'api'));
        $parentModel=ParentModel::create([
                'user_id' => $user->id,
                'name' => fake()->name(),
                'phone_number' => "094949494",
            ]);

        ParentStudent::create([
            'student_id' => 1,
            'parent_model_id' => $parentModel->id,
        ]);
    }
}
