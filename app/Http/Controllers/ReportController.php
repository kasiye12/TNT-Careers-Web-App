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
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('role:admin,hr_manager'),
        ];
    }

    public function vacancyProgress()
    {
        $vacancies = Vacancy::withCount(['applications', 'applications as shortlisted_count' => function ($query) {
            $query->where('status', 'shortlisted');
        }, 'applications as selected_count' => function ($query) {
            $query->where('status', 'selected');
        }])->orderBy('created_at', 'desc')->get();

        return view('reports.vacancy-progress', compact('vacancies'));
    }

    public function genderDemographics()
    {
        $genderStats = Applicant::selectRaw('gender, COUNT(*) as count')
            ->groupBy('gender')
            ->get();

        $regionalGenderStats = Applicant::selectRaw('region, gender, COUNT(*) as count')
            ->groupBy('region', 'gender')
            ->get()
            ->groupBy('region');

        return view('reports.demographics', compact('genderStats', 'regionalGenderStats'));
    }

    public function exportApplications(Request $request)
    {
        $request->validate([
            'vacancy_id' => 'required|exists:vacancies,id',
            'format' => 'required|in:excel,pdf',
        ]);

        $vacancy = Vacancy::find($request->vacancy_id);
        $applications = Application::with(['applicant', 'evaluationScores'])
            ->where('vacancy_id', $request->vacancy_id)
            ->get();

        if ($request->format === 'excel') {
            return Excel::download(
                new ApplicationsExport($vacancy, $applications),
                "applications_{$vacancy->vacancy_number}.xlsx"
            );
        }

        $pdf = PDF::loadView('reports.exports.applications-pdf', [
            'vacancy' => $vacancy,
            'applications' => $applications,
        ]);

        return $pdf->download("applications_{$vacancy->vacancy_number}.pdf");
    }

    public function exportDemographics()
    {
        return Excel::download(
            new DemographicsExport(),
            'demographics_report.xlsx'
        );
    }

    public function shortlistMatrixPDF(Request $request)
    {
        $request->validate([
            'vacancy_id' => 'required|exists:vacancies,id',
        ]);

        $applications = Application::with(['applicant', 'evaluationScores'])
            ->where('vacancy_id', $request->vacancy_id)
            ->whereIn('status', ['interview', 'medical_check'])
            ->get();

        foreach ($applications as $application) {
            $application->academic_score = $application->evaluationScores()
                ->where('evaluation_type', 'academic_experience')
                ->avg('score') ?? 0;
            
            $application->written_score = $application->evaluationScores()
                ->where('evaluation_type', 'written_exam')
                ->avg('score') ?? 0;
            
            $application->interview_score = $application->evaluationScores()
                ->where('evaluation_type', 'panel_interview')
                ->avg('score') ?? 0;
            
            $application->weighted_total = 
                ($application->academic_score * 0.3) + 
                ($application->written_score * 0.4) + 
                ($application->interview_score * 0.3);
        }

        $pdf = PDF::loadView('reports.exports.shortlist-matrix-pdf', [
            'vacancy' => Vacancy::find($request->vacancy_id),
            'applications' => $applications->sortByDesc('weighted_total'),
        ]);

        return $pdf->download('shortlist_matrix.pdf');
    }
}
