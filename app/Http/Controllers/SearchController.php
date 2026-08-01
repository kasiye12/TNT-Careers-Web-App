<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function global(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return back();
        }

        $vacancies = Vacancy::where('title', 'like', "%{$query}%")
            ->orWhere('department', 'like', "%{$query}%")
            ->orWhere('vacancy_number', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        $applications = Application::with(['vacancy', 'applicant'])
            ->whereHas('applicant', function($q) use ($query) {
                $q->where('first_name_en', 'like', "%{$query}%")
                  ->orWhere('father_name_en', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return view('search.results', compact('query', 'vacancies', 'applications', 'users'));
    }
}
