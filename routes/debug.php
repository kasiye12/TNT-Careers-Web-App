<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/debug-login-test', function () {
    $result = [];
    
    // Test credentials
    $result['admin_auth'] = Auth::attempt(['email' => 'admin@tnt-constructions.com', 'password' => 'password']);
    Auth::logout();
    
    $result['hr_auth'] = Auth::attempt(['email' => 'hr@tnt-constructions.com', 'password' => 'password']);
    Auth::logout();
    
    $result['applicant_auth'] = Auth::attempt(['email' => 'applicant@example.com', 'password' => 'password']);
    Auth::logout();
    
    $result['users_count'] = \App\Models\User::count();
    $result['roles_count'] = \Spatie\Permission\Models\Role::count();
    
    return response()->json($result);
});
