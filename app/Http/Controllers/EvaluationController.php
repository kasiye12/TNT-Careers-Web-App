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
     * HR can only evaluate own department
     * Evaluator can only evaluate own department
     * Admin can evaluate any department
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

        // STRICT EVALUATION ACCESS:
        // Admin: Can evaluate all
        // HR/Evaluator: Can only evaluate their own department
        if ($user->user_type !== 'admin') {
            if (!$this->departmentsMatch($userDepartment, $vacancyDepartment)) {
                return back()->with('error', 
                    '❌ Access Denied: You can only evaluate candidates from your department (' . 
                    $userDepartment . '). This candidate belongs to ' . $vacancyDepartment . '.'
                );
            }
        }

        // Create or update score
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

        EvaluationScore::create([
            'application_id' => $application->id,
            'evaluator_id' => $user->id,
            'evaluator_department' => $userDepartment,
            'evaluation_type' => $request->evaluation_type,
            'score' => $request->score,
            'max_score' => 100,
            'weight_percentage' => $this->getWeight($request->evaluation_type),
            'comments' => $request->comments,
        ]);

        return back()->with('success', '✅ Score submitted!');
    }

    /**
     * VIEW scorecard - HR can view ALL departments
     * Evaluator can view only own department
     */
    public function getScorecard(Application $application)
    {
        $user = Auth::user();
        $userDepartment = $user->department;
        $vacancyDepartment = $application->vacancy->department ?? 'General';
        
        // HR can VIEW all departments (but can only EVALUATE own)
        // Evaluator can only VIEW own department
        if ($user->user_type === 'evaluator') {
            if (!$this->departmentsMatch($userDepartment, $vacancyDepartment)) {
                abort(403, '❌ Access Denied: You can only view candidates from your department.');
            }
        }
        // HR Manager - allowed to view all (no restriction on viewing)
        
        $application->load(['evaluationScores.evaluator', 'vacancy', 'applicant']);
        $allScores = $application->evaluationScores;
        
        $academicScore = $allScores->where('evaluation_type', 'academic_experience')->avg('score') ?? 0;
        $writtenScore = $allScores->where('evaluation_type', 'written_exam')->avg('score') ?? 0;
        $interviewScore = $allScores->where('evaluation_type', 'panel_interview')->avg('score') ?? 0;
        $weightedTotal = ($academicScore * 0.3) + ($writtenScore * 0.4) + ($interviewScore * 0.3);

        return view('evaluations.scorecard', compact(
            'application', 'academicScore', 'writtenScore', 'interviewScore', 
            'weightedTotal', 'allScores'
        ));
    }

    /**
     * Admin can edit any score
     * HR can edit only own department's scores
     * Evaluator can edit only own scores
     */
    public function updateScoreAsAdmin(Request $request, EvaluationScore $score)
    {
        $user = Auth::user();
        
        // Admin: Full access
        if ($user->user_type === 'admin') {
            $score->update(['score' => $request->score, 'comments' => $request->comments]);
            return back()->with('success', '✅ Score updated by Admin.');
        }
        
        // HR: Can only edit own department's scores
        if ($user->user_type === 'hr_manager') {
            $hrDepartment = $user->department;
            $scoreDepartment = $score->evaluator_department;
            
            if (!$this->departmentsMatch($hrDepartment, $scoreDepartment)) {
                return back()->with('error', 
                    '❌ Cannot edit: This evaluation was submitted by ' . $scoreDepartment . 
                    ' department. You can only edit ' . $hrDepartment . ' department evaluations.'
                );
            }
            
            $score->update(['score' => $request->score, 'comments' => $request->comments]);
            return back()->with('success', '✅ Score updated.');
        }
        
        // Evaluator: Can only edit own scores
        if ($user->id === $score->evaluator_id) {
            $score->update(['score' => $request->score, 'comments' => $request->comments]);
            return back()->with('success', '✅ Your score updated.');
        }
        
        abort(403, '❌ You cannot edit this evaluation.');
    }

    /**
     * Delete score
     */
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
                $app->academic_score = $app->evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                $app->written_score = $app->evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                $app->interview_score = $app->evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                $app->weighted_total = ($app->academic_score * 0.3) + ($app->written_score * 0.4) + ($app->interview_score * 0.3);
                return $app;
            })->sortByDesc('weighted_total');

        return view('evaluations.shortlist-matrix', compact('applications'));
    }

    private function getWeight($type)
    {
        return match($type) {
            'academic_experience' => 30, 'written_exam' => 40, 'panel_interview' => 30,
            default => 0,
        };
    }

    private function departmentsMatch($dept1, $dept2)
    {
        if (!$dept1 || !$dept2) return true;
        $dept1 = strtolower(trim($dept1));
        $dept2 = strtolower(trim($dept2));
        return $dept1 === $dept2 || str_contains($dept1, $dept2) || str_contains($dept2, $dept1);
    }
}
