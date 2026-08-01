<?php

namespace App\Services;

use App\Models\Application;

class AutoScreeningService
{
    public function screenApplication(Application $application): array
    {
        $results = [];
        $vacancy = $application->vacancy;
        $applicant = $application->applicant;

        if ($applicant->total_years_exp >= $vacancy->min_years_experience) {
            $results[] = ['criteria' => 'experience', 'passed' => true, 'message' => 'Experience requirement met.'];
        } else {
            $results[] = ['criteria' => 'experience', 'passed' => false, 'message' => "Requires {$vacancy->min_years_experience} years, has {$applicant->total_years_exp}."];
        }

        return $results;
    }
}
