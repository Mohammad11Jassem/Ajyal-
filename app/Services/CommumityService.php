<?php

namespace App\Services;

use App\Models\Community;

class CommumityService
{
    public function getByCurriculum($curriculumId) {
        return Community::where('curriculum_id', $curriculumId)->with('issues')->first();
    }
}
