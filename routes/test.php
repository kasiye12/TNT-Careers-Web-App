<?php

use Illuminate\Support\Facades\Route;

Route::get('/test-system', function () {
    $checks = [];
    
    // Check packages
    $checks['activitylog'] = class_exists(\Spatie\Activitylog\Traits\LogsActivity::class);
    $checks['permission'] = class_exists(\Spatie\Permission\Traits\HasRoles::class);
    $checks['dompdf'] = class_exists(\Barryvdh\DomPDF\Facade\Pdf::class);
    $checks['excel'] = class_exists(\Maatwebsite\Excel\Facades\Excel::class);
    $checks['sanctum'] = class_exists(\Laravel\Sanctum\HasApiTokens::class);
    
    // Check database
    try {
        $checks['database'] = \DB::connection()->getPdo() ? true : false;
    } catch (\Exception $e) {
        $checks['database'] = false;
    }
    
    // Check models
    $checks['User_model'] = class_exists(\App\Models\User::class);
    $checks['Vacancy_model'] = class_exists(\App\Models\Vacancy::class);
    $checks['Application_model'] = class_exists(\App\Models\Application::class);
    
    return response()->json([
        'status' => 'ok',
        'checks' => $checks,
        'timestamp' => now()->toDateTimeString(),
    ]);
});
