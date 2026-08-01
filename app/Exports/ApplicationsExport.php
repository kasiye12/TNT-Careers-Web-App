<?php

namespace App\Exports;

use App\Models\Vacancy;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ApplicationsExport implements FromView, ShouldAutoSize
{
    protected $vacancy;
    protected $applications;

    public function __construct(Vacancy $vacancy, $applications)
    {
        $this->vacancy = $vacancy;
        $this->applications = $applications;
    }

    public function view(): View
    {
        return view('exports.applications', [
            'vacancy' => $this->vacancy,
            'applications' => $this->applications,
        ]);
    }
}
