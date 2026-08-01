<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index()
    {
        $user = Auth::user();
        
        // Simple string-based redirect
        if ($user->user_type === 'applicant') {
            return redirect('/applicant/dashboard');
        }
        
        // Admin, HR, Evaluator all go to HR dashboard
        return redirect('/hr/dashboard');
    }
}
