<?php

namespace App\Exports;

use App\Models\Application;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ApplicationsExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $applications = Application::with(['vacancy', 'applicant.user'])->latest()->get();
        
        return view('exports.applications', compact('applications'));
    }
}
