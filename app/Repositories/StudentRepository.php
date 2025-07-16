<?php

namespace App\Repositories;

use App\Interfaces\StudentInterface;
use App\Models\Student;
use App\Models\User;

class StudentRepository implements StudentInterface
{
    public function store(array $data)
    {
        return Student::create($data);
    }

    public function getStudentByCodeAndName(array $data){

        return Student::where('access_code', $data['access_code'])
                        ->where('first_name', $data['first_name'])
                        ->where('last_name', $data['last_name'])
                        ->first();
    }


}
