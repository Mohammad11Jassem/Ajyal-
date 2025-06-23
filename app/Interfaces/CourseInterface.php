<?php

namespace App\Interfaces;

interface CourseInterface
{
    //
    public function store(array $data);
    public function show($id);

    public function destroy($id);
}
