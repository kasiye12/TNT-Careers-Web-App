<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Vacancy;
use App\Services\AutoScreeningService;
use App\Services\ApplicationPDFService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ApplicationController extends Controller implements HasMiddleware
{
    protected $screeningService;
    protected $pdfService;

    public function __construct(AutoScreeningService $screeningService, ApplicationPDFService $pdfService)
    {
        $this->screeningService = $screeningService;
        $this->pdfService = $pdfService;
    }

    public static function middleware(): array
    {
        return [new Middleware('auth')];
    }

    // APPLICANT METHODS
    public function apply(Vacancy $vacancy)
    {
        $user = Auth::user();
        if ($user->user_type !== 'applicant') return redirect()->route('home')->with('error', 'Only applicants can apply.');
        
        $applicant = $user->applicant;
        if (!$applicant) return redirect()->route('applicant.profile.create')->with('warning', 'Create profile first.');
        if (!$applicant->profile_completed) {
            if (!$applicant->educationHistories()->exists()) return redirect()->route('applicant.education.create')->with('warning', 'Add education first.');
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
        if (!$applicant || !$applicant->profile_completed) return redirect()->route('applicant.profile.create')->with('error', 'Complete profile first.');
        
        $request->validate(['declaration_accepted' => 'required|accepted']);
        
        if (Application::where('vacancy_id', $vacancy->id)->where('applicant_id', $applicant->id)->exists()) {
            return redirect()->route('applicant.applications')->with('info', 'Already applied.');
        }
        
        Application::create([
            'vacancy_id' => $vacancy->id,
            'applicant_id' => $applicant->id,
            'status' => 'submitted',
            'declaration_accepted' => true,
            'submitted_at' => now(),
        ]);
        
        return redirect()->route('applicant.applications')->with('success', '✅ Application submitted!');
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
        $application->load(['vacancy', 'applicant.user', 'applicant.educationHistories', 'applicant.workExperiences', 'interviews', 'evaluationScores']);
        return view('applications.show', compact('application'));
    }

    // HR METHODS
    public function reviewIndex()
    {
        $applications = Application::with(['vacancy', 'applicant.user'])
            ->where('status', 'submitted')
            ->latest()
            ->paginate(20);
        return view('hr.applications.review', compact('applications'));
    }

    public function pipeline()
    {
        return view('hr.applications.pipeline');
    }

    /**
     * UPDATE APPLICATION STATUS - APPROVE / REJECT / MOVE STAGE
     */
    public function updateStatus(Request $request, Application $application)
    {
        $validStatuses = [
            'document_verified', 'shortlisted', 
            'written_exam', 'interview', 'medical_check', 
            'selected', 'rejected'
        ];
        
        $request->validate([
            'status' => 'required|in:' . implode(',', $validStatuses),
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $application->status;
        $newStatus = $request->status;
        
        // Update application
        $application->update([
            'status' => $newStatus,
            'rejection_reason' => $newStatus === 'rejected' ? ($request->notes ?: 'Application rejected') : null,
        ]);

        // Log status change
        try {
            $application->statusLogs()->create([
                'changed_by' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $request->notes ?? 'Status updated to ' . ucwords(str_replace('_', ' ', $newStatus)),
            ]);
        } catch (\Exception $e) {
            // Continue even if logging fails
        }

        // Success messages
        $messages = [
            'document_verified' => '✅ Documents verified successfully!',
            'shortlisted' => '⭐ Candidate shortlisted! Move to next stage.',
            'written_exam' => '📝 Candidate moved to Written Exam stage.',
            'interview' => '🎤 Candidate moved to Interview stage.',
            'medical_check' => '🏥 Candidate moved to Medical Check stage.',
            'selected' => '🎉 Candidate SELECTED! You can now generate an offer letter.',
            'rejected' => '❌ Application rejected.',
        ];

        return back()->with('success', $messages[$newStatus] ?? 'Status updated successfully!');
    }

    public function shortlistedCandidates()
    {
        $applications = Application::with(['vacancy', 'applicant.user'])
            ->where('status', 'shortlisted')
            ->latest()
            ->paginate(20);
        return view('hr.applications.shortlisted', compact('applications'));
    }

    public function downloadHRPDF(Application $application)
    {
        $pdfPath = storage_path("app/private/applications/{$application->id}/HR_Application_{$application->id}.pdf");
        if (!file_exists($pdfPath)) {
            try {
                $pdfPath = $this->pdfService->generateHRMasterPDF($application);
            } catch (\Exception $e) {
                return back()->with('error', 'Could not generate PDF.');
            }
        }
        return response()->download($pdfPath);
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
}
