<?php

namespace App\View\Components;

use Illuminate\View\Component;

class QuickStats extends Component
{
    public function render()
    {
        $stats = [
            'vacancies' => \App\Models\Vacancy::count(),
            'applications' => \App\Models\Application::count(),
            'users' => \App\Models\User::count(),
            'published' => \App\Models\Vacancy::where('status', 'published')->count(),
        ];
        
        return view('components.quick-stats', compact('stats'));
    }
}
