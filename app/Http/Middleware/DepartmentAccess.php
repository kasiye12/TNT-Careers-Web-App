<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DepartmentAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Admin - always allowed
        if ($user->user_type === 'admin') {
            return $next($request);
        }
        
        // For HR and Evaluator
        if (in_array($user->user_type, ['hr_manager', 'evaluator'])) {
            $userDepartment = $user->department;
            
            // If no department, still allow access but show warning
            if (!$userDepartment) {
                // Auto-assign a default department for HR
                if ($user->user_type === 'hr_manager') {
                    $user->update(['department' => 'Human Resource Development and Administration Department']);
                    $userDepartment = $user->department;
                }
                // For evaluator, auto-assign Engineering
                if ($user->user_type === 'evaluator') {
                    $user->update(['department' => 'Engineering Department']);
                    $userDepartment = $user->department;
                }
            }
            
            session([
                'user_department' => $userDepartment,
                'can_view_all' => ($user->user_type === 'hr_manager'),
                'can_evaluate_all' => false,
            ]);
            
            return $next($request);
        }
        
        return $next($request);
    }
}
