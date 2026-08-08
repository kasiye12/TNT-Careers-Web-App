<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\EvaluationScore;
use App\Models\Interview;
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
            new Middleware('department'),
            new Middleware('role:admin,hr_manager,evaluator'),
        ];
    }

    /**
     * Submit evaluation score
     */
    public function scoreApplication(Request $request, Application $application)
    {
        $request->validate([
            'evaluation_type' => 'required|in:academic_experience,written_exam,panel_interview',
            'score' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string',
        ]);

        $user = Auth::user();
        $userDepartment = $user->department ?? 'General';
        $vacancyDepartment = $application->vacancy->department ?? 'General';

        if ($user->user_type !== 'admin') {
            if (!$this->departmentsMatch($userDepartment, $vacancyDepartment)) {
                return back()->with('error', '❌ Access Denied: You can only evaluate candidates from your department (' . $userDepartment . '). This candidate belongs to ' . $vacancyDepartment . '.');
            }
        }

        $existingScore = EvaluationScore::where('application_id', $application->id)
            ->where('evaluator_id', $user->id)
            ->where('evaluation_type', $request->evaluation_type)
            ->first();

        if ($existingScore) {
            $existingScore->update([
                'score' => $request->score,
                'comments' => $request->comments,
                'evaluator_department' => $userDepartment,
            ]);
            return back()->with('success', '✅ Your score updated!');
        }

        $weight = 30;
        if ($request->evaluation_type === 'written_exam') $weight = 40;
        if ($request->evaluation_type === 'panel_interview') $weight = 30;

        EvaluationScore::create([
            'application_id' => $application->id,
            'evaluator_id' => $user->id,
            'evaluator_department' => $userDepartment,
            'evaluation_type' => $request->evaluation_type,
            'score' => $request->score,
            'max_score' => 100,
            'weight_percentage' => $weight,
            'comments' => $request->comments,
        ]);

        return back()->with('success', '✅ Score submitted!');
    }

    /**
     * VIEW scorecard with SMART WEIGHTING
     */
    public function getScorecard(Application $application)
    {
        $user = Auth::user();
        $userDepartment = $user->department;
        $vacancyDepartment = $application->vacancy->department ?? 'General';
        
        if ($user->user_type === 'evaluator') {
            if (!$this->departmentsMatch($userDepartment, $vacancyDepartment)) {
                abort(403, '❌ Access Denied: You can only view candidates from your department.');
            }
        }
        
        $application->load(['evaluationScores.evaluator', 'vacancy', 'applicant']);
        $allScores = $application->evaluationScores;
        
        $hasWrittenScore = $allScores->where('evaluation_type', 'written_exam')->count() > 0;
        
        $academicScore = $allScores->where('evaluation_type', 'academic_experience')->avg('score') ?? 0;
        $writtenScore = $allScores->where('evaluation_type', 'written_exam')->avg('score') ?? 0;
        $interviewScore = $allScores->where('evaluation_type', 'panel_interview')->avg('score') ?? 0;
        
        if ($hasWrittenScore) {
            $academicWeight = 30;
            $writtenWeight = 40;
            $interviewWeight = 30;
            $showWrittenSection = true;
            $evaluationNote = null;
        } else {
            $academicWeight = 40;
            $writtenWeight = 0;
            $interviewWeight = 60;
            $showWrittenSection = false;
            $evaluationNote = '⚠️ Written Exam was skipped for this candidate. Weights adjusted: Academic 40% + Interview 60%.';
        }
        
        $weightedTotal = ($academicScore * $academicWeight / 100) + ($writtenScore * $writtenWeight / 100) + ($interviewScore * $interviewWeight / 100);

        return view('evaluations.scorecard', compact(
            'application', 'allScores',
            'academicScore', 'writtenScore', 'interviewScore', 'weightedTotal',
            'hasWrittenScore', 'showWrittenSection', 'evaluationNote',
            'academicWeight', 'writtenWeight', 'interviewWeight'
        ));
    }

    public function updateScoreAsAdmin(Request $request, EvaluationScore $score)
    {
        $user = Auth::user();
        
        if ($user->user_type === 'admin') {
            $score->update(['score' => $request->score, 'comments' => $request->comments]);
            return back()->with('success', '✅ Score updated by Admin.');
        }
        
        if ($user->user_type === 'hr_manager') {
            if (!$this->departmentsMatch($user->department, $score->evaluator_department)) {
                return back()->with('error', '❌ Cannot edit: This evaluation belongs to another department.');
            }
            $score->update(['score' => $request->score, 'comments' => $request->comments]);
            return back()->with('success', '✅ Score updated.');
        }
        
        if ($user->id === $score->evaluator_id) {
            $score->update(['score' => $request->score, 'comments' => $request->comments]);
            return back()->with('success', '✅ Your score updated.');
        }
        
        abort(403, '❌ You cannot edit this evaluation.');
    }

    public function deleteScore(EvaluationScore $score)
    {
        $user = Auth::user();
        
        if ($user->user_type === 'admin') {
            $score->delete();
            return back()->with('success', 'Score deleted.');
        }
        
        if ($user->user_type === 'hr_manager') {
            if ($this->departmentsMatch($user->department, $score->evaluator_department)) {
                $score->delete();
                return back()->with('success', 'Score deleted.');
            }
            return back()->with('error', '❌ Cannot delete: This evaluation belongs to another department.');
        }
        
        if ($user->id === $score->evaluator_id) {
            $score->delete();
            return back()->with('success', 'Your score deleted.');
        }
        
        abort(403);
    }

    public function scheduleInterview(Request $request, Application $application)
    {
        $request->validate([
            'interview_type' => 'required|in:written_exam,panel_interview,technical_test',
            'scheduled_at' => 'required|date|after:now',
            'venue' => 'required|string|max:255',
        ]);

        Interview::create([
            'application_id' => $application->id,
            'interview_type' => $request->interview_type,
            'scheduled_at' => $request->scheduled_at,
            'venue' => $request->venue,
            'instructions' => $request->instructions,
            'status' => 'scheduled',
        ]);

        $application->update(['status' => $request->interview_type === 'written_exam' ? 'written_exam' : 'interview']);
        return back()->with('success', 'Interview scheduled!');
    }

    public function shortlistMatrix(Request $request)
    {
        $request->validate(['vacancy_id' => 'required|exists:vacancies,id']);
        
        $applications = Application::with(['applicant', 'evaluationScores'])
            ->where('vacancy_id', $request->vacancy_id)
            ->whereIn('status', ['interview', 'medical_check'])
            ->get()
            ->map(function ($app) {
                $scores = $app->evaluationScores;
                $academic = $scores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                $written = $scores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                $interview = $scores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                $hasWritten = $scores->where('evaluation_type','written_exam')->count() > 0;
                
                if ($hasWritten) {
                    $app->weighted_total = ($academic * 0.3) + ($written * 0.4) + ($interview * 0.3);
                } else {
                    $app->weighted_total = ($academic * 0.4) + ($interview * 0.6);
                }
                $app->academic_score = $academic;
                $app->written_score = $written;
                $app->interview_score = $interview;
                return $app;
            })->sortByDesc('weighted_total');

        return view('evaluations.shortlist-matrix', compact('applications'));
    }

    private function departmentsMatch($dept1, $dept2)
    {
        if (!$dept1 || !$dept2) return true;
        $dept1 = strtolower(trim($dept1));
        $dept2 = strtolower(trim($dept2));
        return $dept1 === $dept2 || str_contains($dept1, $dept2) || str_contains($dept2, $dept1);
    }
}
