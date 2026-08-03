<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DashboardStats extends Component
{
    public $stats;
    
    public function __construct()
    {
        $this->stats = [
            'vacancies' => \App\Models\Vacancy::count(),
            'applications' => \App\Models\Application::count(),
            'shortlisted' => \App\Models\Application::where('status', 'shortlisted')->count(),
            'selected' => \App\Models\Application::where('status', 'selected')->count(),
            'users' => \App\Models\User::count(),
        ];
    }
    
    public function render()
    {
        return view('components.dashboard-stats');
    }
}
