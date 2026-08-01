<?php

namespace App\Services;

use App\Models\Vacancy;

class VacancyNumberGenerator
{
    public function generate(): string
    {
        $year = date('Y');
        $count = Vacancy::whereYear('created_at', $year)->count() + 1;
        return sprintf('TNT-VAC-%s-%03d', $year, $count);
    }
}
