<?php

namespace App\Exports;

use App\Models\Applicant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DemographicsExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $genderStats = Applicant::selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->get();

        $regionalStats = Applicant::selectRaw('region, gender, COUNT(*) as count')
            ->groupBy('region', 'gender')
            ->get()
            ->groupBy('region');

        return view('exports.demographics', compact('genderStats', 'regionalStats'));
    }
}
