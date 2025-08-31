<?php

namespace App\Interfaces;

use App\Enum\SubjectType;

interface SubjectRepositoryInterface
{
    public function allWithTopics(?SubjectType $type = null);
    public function all(?SubjectType $type = null);
    public function allArchivedSubjects(?SubjectType $type = null);
    public function findWithTopics($id);
    public function find($id);
    public function create(array $data);
    // public function update($id, array $data);
    public function delete($id);
}
