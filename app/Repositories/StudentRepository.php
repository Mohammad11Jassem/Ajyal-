<?php

namespace App\Repositories;

use App\Interfaces\StudentInterface;
use App\Models\Student;
use App\Models\User;

class StudentRepository implements StudentInterface
{
    public function store(array $data)
    {
        return Student::create([
            'user_id'=>$data['user_id'],
            'first_name'=>$data['first_name'],
            'last_name'=>$data['last_name'],
            'father_name'=>$data['father_name'],
            'mother_name'=>$data['mother_name'],
            'number_civial'=>$data['number_civial'],
            'address'=>$data['address'],
            'class_level'=>$data['class_level'],
            'birthdate'=>$data['birthdate'],
            'access_code'=>$data['access_code'],
        ]);
    }

    public function getStudentByCodeAndName(array $data){

        return Student::where('access_code', $data['access_code'])
                        ->where('first_name', $data['first_name'])
                        ->where('last_name', $data['last_name'])
                        ->first();
    }


}
