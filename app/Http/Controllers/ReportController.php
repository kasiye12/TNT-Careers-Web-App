<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;
use App\Models\Application;
use App\Models\Applicant;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplicationsExport;
use App\Exports\DemographicsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function vacancyProgress()
    {
        $vacancies = Vacancy::withCount(['applications', 
            'applications as shortlisted_count' => fn($q) => $q->where('status', 'shortlisted'),
            'applications as selected_count' => fn($q) => $q->where('status', 'selected')
        ])->orderBy('created_at', 'desc')->get();

        return view('reports.vacancy-progress', compact('vacancies'));
    }

    public function genderDemographics()
    {
        $genderStats = Applicant::selectRaw('gender, COUNT(*) as count')->groupBy('gender')->get();
        $regionalGenderStats = Applicant::selectRaw('region, gender, COUNT(*) as count')
            ->groupBy('region', 'gender')->get()->groupBy('region');

        return view('reports.demographics', compact('genderStats', 'regionalGenderStats'));
    }

    public function exportApplications(Request $request)
    {
        try {
            return Excel::download(new ApplicationsExport, 'applications_' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function exportDemographics()
    {
        try {
            return Excel::download(new DemographicsExport, 'demographics_' . now()->format('Y-m-d') . '.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function shortlistMatrixPDF(Request $request)
    {
        $applications = Application::with(['applicant', 'evaluationScores'])
            ->whereIn('status', ['interview', 'medical_check'])
            ->get()
            ->map(function ($app) {
                $app->academic_score = $app->evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                $app->written_score = $app->evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                $app->interview_score = $app->evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                $app->weighted_total = ($app->academic_score * 0.3) + ($app->written_score * 0.4) + ($app->interview_score * 0.3);
                return $app;
            })->sortByDesc('weighted_total');

        $pdf = PDF::loadView('reports.exports.shortlist-matrix-pdf', [
            'vacancy' => Vacancy::find($request->vacancy_id),
            'applications' => $applications,
        ]);

        return $pdf->download('shortlist_matrix.pdf');
    }
}
