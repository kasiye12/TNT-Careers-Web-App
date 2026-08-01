<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use App\Services\VacancyNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class VacancyController extends Controller implements HasMiddleware
{
    protected $numberGenerator;

    public function __construct(VacancyNumberGenerator $numberGenerator)
    {
        $this->numberGenerator = $numberGenerator;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['publicIndex', 'publicShow', 'apiLatest']),
            new Middleware('role:admin,hr_manager', except: ['publicIndex', 'publicShow', 'apiLatest']),
        ];
    }

    // PUBLIC - Job Listings with Search & Filter
    public function publicIndex(Request $request)
    {
        $query = Vacancy::where('status', 'published')
            ->where('closing_date', '>=', now());

        // Search by title, department, or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('duty_station', 'like', "%{$search}%")
                  ->orWhere('vacancy_number', 'like', "%{$search}%");
            });
        }

        // Filter by category/department
        if ($request->filled('category')) {
            $query->where('department', 'like', "%{$request->category}%");
        }

        // Filter by location
        if ($request->filled('location')) {
            if ($request->location === 'head_office') {
                $query->where('duty_station_category', 'head_office');
            } elseif ($request->location === 'project_site') {
                $query->where('duty_station_category', 'project_site');
            }
        }

        // Filter by employment type
        if ($request->filled('type')) {
            $query->where('employment_type', $request->type);
        }

        $vacancies = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
        
        // Get unique departments for filter dropdown
        $departments = Vacancy::where('status', 'published')
            ->distinct()
            ->pluck('department')
            ->filter()
            ->values();

        return view('public.vacancies', compact('vacancies', 'departments'));
    }

    // PUBLIC - Job Detail
    public function publicShow(Vacancy $vacancy)
    {
        if ($vacancy->status !== 'published' && !Auth::check()) {
            abort(404);
        }
        return view('public.vacancy-detail', compact('vacancy'));
    }

    // API for WordPress
    public function apiLatest()
    {
        $vacancies = Vacancy::published()
            ->select('id', 'title', 'department', 'duty_station', 'closing_date')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($vacancy) {
                return [
                    'id' => $vacancy->id,
                    'title' => $vacancy->title,
                    'department' => $vacancy->department,
                    'duty_station' => $vacancy->duty_station,
                    'closing_date' => $vacancy->closing_date->format('Y-m-d'),
                    'apply_url' => route('vacancies.public.show', $vacancy->id),
                ];
            });

        return response()->json($vacancies);
    }

    // HR Methods
    public function index()
    {
        $vacancies = Vacancy::withCount('applications')->orderBy('created_at', 'desc')->paginate(20);
        return view('hr.vacancies.index', compact('vacancies'));
    }

    public function create()
    {
        $jobCategories = [
            'executive_management' => 'Executive Management',
            'project_engineering' => 'Project Engineering',
            'office_engineering' => 'Office Engineering',
            'occupational_health_safety' => 'Occupational Health & Safety',
            'finance_accounting' => 'Finance & Accounting',
            'equipment_logistics' => 'Equipment & Logistics',
            'trade_tvet_foremen' => 'Trade & TVET Foremen',
            'other' => 'Other',
        ];
        return view('hr.vacancies.create', compact('jobCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'job_category' => 'required|string',
            'department' => 'required|string|max:100',
            'duty_station_category' => 'required|in:head_office,project_site',
            'duty_station' => 'required|string|max:100',
            'employment_type' => 'required|in:permanent,contract,project_based,temporary',
            'positions_count' => 'required|integer|min:1',
            'salary_type' => 'required|in:fixed,negotiable,scale',
            'min_years_experience' => 'required|integer|min:0',
            'min_education_level' => 'required|string',
            'opening_date' => 'required|date',
            'closing_date' => 'required|date|after:opening_date',
            'description_en' => 'nullable|string',
            'description_am' => 'nullable|string',
            'requirements_en' => 'nullable|string',
            'requirements_am' => 'nullable|string',
        ]);

        $validated['vacancy_number'] = $this->numberGenerator->generate();
        $validated['created_by'] = Auth::id();
        $validated['status'] = request('status', 'draft');

        Vacancy::create($validated);

        return redirect()->route('hr.vacancies.index')->with('success', 'Vacancy created successfully!');
    }

    public function edit(Vacancy $vacancy)
    {
        return view('hr.vacancies.edit', compact('vacancy'));
    }

    public function update(Request $request, Vacancy $vacancy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'required|string|max:100',
            'duty_station' => 'required|string|max:100',
            'employment_type' => 'required|in:permanent,contract,project_based,temporary',
            'positions_count' => 'required|integer|min:1',
            'min_years_experience' => 'required|integer|min:0',
            'min_education_level' => 'required|string',
            'closing_date' => 'required|date',
            'description_en' => 'nullable|string',
            'description_am' => 'nullable|string',
            'requirements_en' => 'nullable|string',
        ]);

        $vacancy->update($validated);
        return redirect()->route('hr.vacancies.index')->with('success', 'Vacancy updated!');
    }

    public function publish(Vacancy $vacancy)
    {
        $vacancy->update(['status' => 'published']);
        return back()->with('success', 'Vacancy published!');
    }

    public function close(Vacancy $vacancy)
    {
        $vacancy->update(['status' => 'closed']);
        return back()->with('success', 'Vacancy closed.');
    }

    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return redirect()->route('hr.vacancies.index')->with('success', 'Vacancy archived.');
    }
}
