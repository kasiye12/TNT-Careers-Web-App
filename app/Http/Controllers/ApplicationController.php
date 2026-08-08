<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            // Temporarily remove department middleware from this controller
            // new Middleware('department'),
        ];
    }

    public function apply(Vacancy $vacancy)
    {
        $user = Auth::user();
        if ($user->user_type !== 'applicant') return redirect()->route('home')->with('error', 'Only applicants can apply.');
        
        $applicant = $user->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create')->with('warning', 'Create profile first.');
        if (!$applicant->profile_completed && $applicant->educationHistories()->exists()) {
            $applicant->update(['profile_completed' => true]);
        }
        if (Application::where('vacancy_id', $vacancy->id)->where('applicant_id', $applicant->id)->exists()) {
            return redirect()->route('applicant.applications')->with('info', 'Already applied.');
        }
        if (!$vacancy->isOpen()) return redirect()->route('vacancies.public.index')->with('error', 'Vacancy closed.');
        return view('applicant.apply', compact('vacancy', 'applicant'));
    }

    public function store(Request $request, Vacancy $vacancy)
    {
        $applicant = Auth::user()->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create');
        $request->validate(['declaration_accepted' => 'required|accepted']);
        if (Application::where('vacancy_id', $vacancy->id)->where('applicant_id', $applicant->id)->exists()) {
            return redirect()->route('applicant.applications')->with('info', 'Already applied.');
        }
        Application::create([
            'vacancy_id' => $vacancy->id, 'applicant_id' => $applicant->id,
            'status' => 'submitted', 'declaration_accepted' => true, 'submitted_at' => now(),
        ]);
        return redirect()->route('applicant.applications')->with('success', '🎉 Application submitted!');
    }

    public function myApplications()
    {
        $applicant = Auth::user()->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create');
        $applications = Application::with('vacancy')->where('applicant_id', $applicant->id)->latest()->paginate(10);
        return view('applicant.applications', compact('applications'));
    }

    public function show(Application $application)
    {
        $application->load(['vacancy', 'applicant.user', 'applicant.educationHistories', 'applicant.workExperiences', 'applicant.documents', 'evaluationScores.evaluator']);
        return view('applications.show', compact('application'));
    }

    public function reviewIndex()
    {
        try {
            $query = Application::with(['vacancy', 'applicant.user'])->where('status', 'submitted');
            $applications = $query->latest()->paginate(20);
            return view('hr.applications.review', compact('applications'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading applications: ' . $e->getMessage());
        }
    }

    public function pipeline() { return view('hr.applications.pipeline'); }

    public function updateStatus(Request $request, Application $application)
    {
        $validStatuses = ['document_verified', 'shortlisted', 'written_exam', 'interview', 'medical_check', 'selected', 'rejected'];
        $request->validate(['status' => 'required|in:' . implode(',', $validStatuses)]);
        $application->update(['status' => $request->status, 'rejection_reason' => $request->status === 'rejected' ? ($request->notes ?: 'Rejected') : null]);
        return back()->with('success', '✅ Status updated!');
    }

    public function shortlistedCandidates()
    {
        $query = Application::with(['vacancy', 'applicant.user'])->where('status', 'shortlisted');
        $applications = $query->latest()->paginate(20);
        return view('hr.applications.shortlisted', compact('applications'));
    }

    public function search(Request $request)
    {
        $query = Application::with(['vacancy', 'applicant.user']);
        if ($request->filled('vacancy_id')) $query->where('vacancy_id', $request->vacancy_id);
        if ($request->filled('status')) $query->where('status', $request->status);
        $applications = $query->latest()->paginate(20);
        $vacancies = Vacancy::pluck('title', 'id');
        return view('hr.applications.search', compact('applications', 'vacancies'));
    }

    public function downloadHRPDF(Application $application)
    {
        $pdfPath = storage_path("app/private/applications/{$application->id}/HR_Application_{$application->id}.pdf");
        if (!file_exists($pdfPath)) {
            try { $pdfService = app(\App\Services\ApplicationPDFService::class); $pdfPath = $pdfService->generateHRMasterPDF($application); } 
            catch (\Exception $e) { return back()->with('error', 'Could not generate PDF.'); }
        }
        return response()->download($pdfPath);
    }
}
