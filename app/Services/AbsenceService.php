<?php

namespace App\Services;

use App\Models\Absence;

class AbsenceService
{
    public function store($data)
    {
        $absences = [];

        foreach ($data['registration_ids'] as $registrationId) {
            $absences[] = Absence::create([
                'absences_date' => $data['absences_date'],
                'registration_id' => $registrationId,
            ]);
        }

        return $absences;
    }
}
