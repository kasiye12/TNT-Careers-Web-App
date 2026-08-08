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
        
        // If not logged in, let auth middleware handle it
        if (!$user) {
            return $next($request);
        }
        
        // Admin - always allowed
        if ($user->user_type === 'admin') {
            return $next($request);
        }
        
        // For HR and Evaluator - auto-assign department if missing
        if (in_array($user->user_type, ['hr_manager', 'evaluator'])) {
            if (!$user->department) {
                // Auto-assign based on user type instead of blocking
                $defaultDept = $user->user_type === 'hr_manager' 
                    ? 'Human Resource Development and Administration Department'
                    : 'Engineering Department';
                
                $user->update(['department' => $defaultDept]);
            }
            
            session([
                'user_department' => $user->department,
                'can_view_all' => ($user->user_type === 'hr_manager'),
                'can_evaluate_all' => false,
            ]);
        }
        
        return $next($request);
    }
}
