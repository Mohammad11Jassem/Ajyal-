<?php

namespace App\Interfaces;

interface StudentInterface
{
    public function store(array $data);

    public function getStudentByCodeAndName(array $data);

    

}
