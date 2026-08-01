<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\EvaluationScore;
use App\Models\Interview;
use App\Models\InterviewPanel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EvaluationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('role:admin,hr_manager,evaluator'),
        ];
    }

    public function scoreApplication(Request $request, Application $application)
    {
        $request->validate([
            'evaluation_type' => 'required|in:academic_experience,written_exam,panel_interview',
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string',
        ]);

        $weights = [
            'academic_experience' => 30,
            'written_exam' => 40,
            'panel_interview' => 30,
        ];

        EvaluationScore::create([
            'application_id' => $application->id,
            'evaluator_id' => Auth::id(),
            'evaluation_type' => $request->evaluation_type,
            'score' => $request->score,
            'max_score' => 100,
            'weight_percentage' => $weights[$request->evaluation_type],
            'comments' => $request->comments,
        ]);

        return back()->with('success', 'Score submitted successfully.');
    }

    public function scheduleInterview(Request $request, Application $application)
    {
        $request->validate([
            'interview_type' => 'required|in:written_exam,panel_interview,technical_test',
            'scheduled_at' => 'required|date|after:now',
            'end_time' => 'nullable|date|after:scheduled_at',
            'venue' => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'panel_members' => 'nullable|array',
            'panel_members.*' => 'exists:users,id',
        ]);

        $interview = Interview::create([
            'application_id' => $application->id,
            'interview_type' => $request->interview_type,
            'scheduled_at' => $request->scheduled_at,
            'end_time' => $request->end_time,
            'venue' => $request->venue,
            'instructions' => $request->instructions,
            'status' => 'scheduled',
        ]);

        if ($request->has('panel_members')) {
            foreach ($request->panel_members as $memberId) {
                InterviewPanel::create([
                    'interview_id' => $interview->id,
                    'user_id' => $memberId,
                    'role' => 'member',
                ]);
            }
        }

        $application->update(['status' => $request->interview_type === 'written_exam' ? 'written_exam' : 'interview']);

        return back()->with('success', 'Interview scheduled successfully.');
    }

    public function getScorecard(Application $application)
    {
        $application->load(['evaluationScores.evaluator', 'vacancy', 'applicant']);

        $academicScore = $application->evaluationScores()
            ->where('evaluation_type', 'academic_experience')
            ->avg('score') ?? 0;

        $writtenScore = $application->evaluationScores()
            ->where('evaluation_type', 'written_exam')
            ->avg('score') ?? 0;

        $interviewScore = $application->evaluationScores()
            ->where('evaluation_type', 'panel_interview')
            ->avg('score') ?? 0;

        $weightedTotal = ($academicScore * 0.3) + ($writtenScore * 0.4) + ($interviewScore * 0.3);

        return view('evaluations.scorecard', compact(
            'application',
            'academicScore',
            'writtenScore',
            'interviewScore',
            'weightedTotal'
        ));
    }

    public function shortlistMatrix(Request $request)
    {
        $request->validate([
            'vacancy_id' => 'required|exists:vacancies,id',
        ]);

        $applications = Application::with(['applicant', 'evaluationScores'])
            ->where('vacancy_id', $request->vacancy_id)
            ->whereIn('status', ['interview', 'medical_check'])
            ->get()
            ->map(function ($application) {
                $academicScore = $application->evaluationScores()
                    ->where('evaluation_type', 'academic_experience')
                    ->avg('score') ?? 0;

                $writtenScore = $application->evaluationScores()
                    ->where('evaluation_type', 'written_exam')
                    ->avg('score') ?? 0;

                $interviewScore = $application->evaluationScores()
                    ->where('evaluation_type', 'panel_interview')
                    ->avg('score') ?? 0;

                $application->academic_score = $academicScore;
                $application->written_score = $writtenScore;
                $application->interview_score = $interviewScore;
                $application->weighted_total = ($academicScore * 0.3) + ($writtenScore * 0.4) + ($interviewScore * 0.3);

                return $application;
            })
            ->sortByDesc('weighted_total');

        return view('evaluations.shortlist-matrix', compact('applications'));
    }
}
