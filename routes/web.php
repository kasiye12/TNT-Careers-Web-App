<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OfferLetterController;
use App\Http\Controllers\ApplicantProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Simple test route - NO AUTH REQUIRED
Route::get('/test', function () {
    return 'Server is working! Current time: ' . now();
});

// Public vacancy routes
Route::get('/vacancies', [VacancyController::class, 'publicIndex'])->name('vacancies.public.index');
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'publicShow'])->name('vacancies.public.show');

// API for WordPress
Route::get('/api/v1/vacancies/latest', [VacancyController::class, 'apiLatest'])->name('api.vacancies.latest');

// Authentication routes
require __DIR__.'/auth.php';

// Dashboard - works for ALL authenticated users
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Simple post-login test
Route::get('/after-login', function () {
    if (Auth::check()) {
        return 'LOGGED IN AS: ' . Auth::user()->email . ' | Type: ' . Auth::user()->user_type;
    }
    return 'NOT LOGGED IN';
})->middleware('auth');

// Applicant routes
Route::middleware(['auth'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->user_type !== 'applicant') {
            return redirect('/dashboard');
        }
        return view('applicant.dashboard');
    })->name('dashboard');

    Route::get('/profile/create', [ApplicantProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ApplicantProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ApplicantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ApplicantProfileController::class, 'update'])->name('profile.update');
    Route::get('/education/create', [ApplicantProfileController::class, 'addEducation'])->name('education.create');
    Route::post('/education', [ApplicantProfileController::class, 'storeEducation'])->name('education.store');
    Route::get('/experience/create', [ApplicantProfileController::class, 'addExperience'])->name('experience.create');
    Route::post('/experience', [ApplicantProfileController::class, 'storeExperience'])->name('experience.store');
    Route::post('/documents', [ApplicantProfileController::class, 'uploadDocument'])->name('documents.upload');
    Route::get('/documents/{document}/download', [ApplicantProfileController::class, 'downloadDocument'])->name('documents.download');
    Route::get('/documents', function () { return view('applicant.documents'); })->name('documents');
    Route::post('/profile/complete', [ApplicantProfileController::class, 'completeProfile'])->name('profile.complete');
    Route::get('/vacancies/{vacancy}/apply', [ApplicationController::class, 'apply'])->name('apply');
    Route::post('/vacancies/{vacancy}/apply', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/applications', [ApplicationController::class, 'myApplications'])->name('applications');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
});

// HR & Admin routes
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', function () {
        if (!in_array(Auth::user()->user_type, ['admin', 'hr_manager', 'evaluator'])) {
            return redirect('/dashboard');
        }
        return view('hr.dashboard');
    })->name('dashboard');

    Route::resource('vacancies', VacancyController::class);
    Route::post('/vacancies/{vacancy}/publish', [VacancyController::class, 'publish'])->name('vacancies.publish');
    Route::post('/vacancies/{vacancy}/close', [VacancyController::class, 'close'])->name('vacancies.close');
    Route::get('/applications/review', [ApplicationController::class, 'reviewIndex'])->name('applications.review');
    Route::get('/applications/shortlisted', [ApplicationController::class, 'shortlistedCandidates'])->name('applications.shortlisted');
    Route::post('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::get('/applications/{application}/hr-pdf', [ApplicationController::class, 'downloadHRPDF'])->name('applications.hr-pdf');
    Route::get('/applications/search', [ApplicationController::class, 'search'])->name('applications.search');
    Route::post('/applications/{application}/scores', [EvaluationController::class, 'scoreApplication'])->name('applications.score');
    Route::post('/applications/{application}/interviews', [EvaluationController::class, 'scheduleInterview'])->name('applications.schedule-interview');
    Route::get('/applications/{application}/scorecard', [EvaluationController::class, 'getScorecard'])->name('applications.scorecard');
    Route::get('/shortlist-matrix', [EvaluationController::class, 'shortlistMatrix'])->name('shortlist-matrix');
    Route::get('/applications/{application}/offer-letter', [OfferLetterController::class, 'generate'])->name('offer-letters.create');
    Route::post('/offer-letters', [OfferLetterController::class, 'store'])->name('offer-letters.store');
    Route::get('/offer-letters/{offerLetter}/preview', [OfferLetterController::class, 'preview'])->name('offer-letters.preview');
    Route::post('/offer-letters/{offerLetter}/send', [OfferLetterController::class, 'send'])->name('offer-letters.send');
    Route::get('/reports/vacancy-progress', [ReportController::class, 'vacancyProgress'])->name('reports.vacancy-progress');
    Route::get('/reports/demographics', [ReportController::class, 'genderDemographics'])->name('reports.demographics');
    Route::post('/reports/export-applications', [ReportController::class, 'exportApplications'])->name('reports.export-applications');
    Route::post('/reports/export-demographics', [ReportController::class, 'exportDemographics'])->name('reports.export-demographics');
    Route::post('/reports/shortlist-matrix-pdf', [ReportController::class, 'shortlistMatrixPDF'])->name('reports.shortlist-matrix-pdf');
});

// Evaluator routes
Route::middleware(['auth'])->group(function () {
    Route::post('/evaluations/{application}', [EvaluationController::class, 'scoreApplication'])->name('evaluations.score');
    Route::get('/evaluations/{application}/scorecard', [EvaluationController::class, 'getScorecard'])->name('evaluations.scorecard');
});

// Offer letter routes
Route::post('/offer-letters/{offerLetter}/respond', [OfferLetterController::class, 'respond'])->name('offer-letters.respond');
Route::get('/offer-letters/{offerLetter}/view', [OfferLetterController::class, 'view'])->name('offer-letters.view');

// Account routes
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/settings', [App\Http\Controllers\AccountController::class, 'settings'])->name('settings');
    Route::put('/email', [App\Http\Controllers\AccountController::class, 'updateEmail'])->name('update-email');
    Route::put('/phone', [App\Http\Controllers\AccountController::class, 'updatePhone'])->name('update-phone');
    Route::put('/password', [App\Http\Controllers\AccountController::class, 'updatePassword'])->name('update-password');
    Route::get('/notifications', [App\Http\Controllers\AccountController::class, 'notifications'])->name('notifications');
    Route::put('/notifications', [App\Http\Controllers\AccountController::class, 'updateNotifications'])->name('update-notifications');
    Route::get('/sessions', [App\Http\Controllers\AccountController::class, 'sessions'])->name('sessions');
    Route::delete('/sessions/{sessionId}', [App\Http\Controllers\AccountController::class, 'terminateSession'])->name('terminate-session');
    Route::delete('/sessions', [App\Http\Controllers\AccountController::class, 'terminateAllSessions'])->name('terminate-all-sessions');
    Route::delete('/delete', [App\Http\Controllers\AccountController::class, 'deleteAccount'])->name('delete');
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', function () {
        return view('admin.users');
    })->name('users');
    
    Route::get('/settings', function () {
        return view('admin.settings');
    })->name('settings');
});

// CV Generator Routes
Route::get('/cv-generator', [App\Http\Controllers\CVGeneratorController::class, 'index'])->name('cv.generator');
Route::post('/cv-generator', [App\Http\Controllers\CVGeneratorController::class, 'generate'])->name('cv.generate');

// Verify all tool routes exist
// Route::get('/cv-generator', ...) -> name('cv.generator')
// Route::get('/salary-calculator', ...) -> name('salary.calculator')  
// Route::get('/interview-tips', ...) -> name('interview.tips')
// Route::get('/job-alerts', ...) -> name('job-alerts')

// ============================================
// TOOLS & RESOURCES ROUTES
// ============================================

// CV Generator
Route::get('/cv-generator', [App\Http\Controllers\CVGeneratorController::class, 'index'])->name('cv.generator');
Route::post('/cv-generator', [App\Http\Controllers\CVGeneratorController::class, 'generate'])->name('cv.generate');

// Salary Calculator  
Route::get('/salary-calculator', [App\Http\Controllers\SalaryCalculatorController::class, 'index'])->name('salary.calculator');
Route::post('/salary-calculate', [App\Http\Controllers\SalaryCalculatorController::class, 'calculate'])->name('salary.calculate');

// Interview Tips
Route::get('/interview-tips', function () { return view('tools.interview-tips'); })->name('interview.tips');

// Job Alerts
Route::get('/job-alerts', [App\Http\Controllers\JobAlertController::class, 'index'])->name('job.alerts');
Route::post('/job-alerts', [App\Http\Controllers\JobAlertController::class, 'subscribe'])->name('job.alerts.subscribe');

// Skills management
Route::middleware(['auth'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/skills', [App\Http\Controllers\ApplicantProfileController::class, 'editSkills'])->name('skills.edit');
    Route::put('/skills', [App\Http\Controllers\ApplicantProfileController::class, 'updateSkills'])->name('skills.update');
    
    // Edit/Delete Education
    Route::get('/education/{education}/edit', [App\Http\Controllers\ApplicantProfileController::class, 'editEducation'])->name('education.edit');
    Route::put('/education/{education}', [App\Http\Controllers\ApplicantProfileController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{education}', [App\Http\Controllers\ApplicantProfileController::class, 'deleteEducation'])->name('education.delete');
    
    // Edit/Delete Experience
    Route::get('/experience/{experience}/edit', [App\Http\Controllers\ApplicantProfileController::class, 'editExperience'])->name('experience.edit');
    Route::put('/experience/{experience}', [App\Http\Controllers\ApplicantProfileController::class, 'updateExperience'])->name('experience.update');
    Route::delete('/experience/{experience}', [App\Http\Controllers\ApplicantProfileController::class, 'deleteExperience'])->name('experience.delete');
});

// Application Pipeline Management
Route::get('/hr/applications/pipeline', [App\Http\Controllers\ApplicationController::class, 'pipeline'])->name('hr.applications.pipeline');

// Resume Builder
Route::get('/resume-builder', [App\Http\Controllers\ResumeBuilderController::class, 'index'])->name('resume.builder');
Route::post('/resume-builder', [App\Http\Controllers\ResumeBuilderController::class, 'generate'])->name('resume.generate');

// Reports
Route::get('/hr/reports', function () { return view('reports.index'); })->name('hr.reports.index');
Route::get('/hr/reports/analytics', function () { return view('hr.reports.analytics'); })->name('hr.reports.analytics');

// Admin User Management Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    
    Route::get('/settings', function () { return view('admin.settings'); })->name('settings');
});

// Global Search
Route::get('/search', [App\Http\Controllers\SearchController::class, 'global'])->name('search');

// System Routes
Route::get('/system/health', [App\Http\Controllers\SystemController::class, 'health'])->name('system.health');
Route::get('/api-docs', function () { return view('api-docs'); })->name('api.docs');

// Evaluator Routes
Route::middleware(['auth'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('evaluator.dashboard');
    })->name('dashboard');
});

// Evaluator scoring routes
Route::middleware(['auth'])->group(function () {
    Route::get('/evaluations/{application}/scorecard', [App\Http\Controllers\EvaluationController::class, 'getScorecard'])->name('evaluations.scorecard');
    Route::post('/evaluations/{application}', [App\Http\Controllers\EvaluationController::class, 'scoreApplication'])->name('evaluations.score');
});

// System overview
Route::get('/system', function () { return view('system.overview'); })->name('system.overview');

// Evaluator summary
Route::get('/evaluator/summary', function () { return view('evaluator.summary'); })->name('evaluator.summary');

// Document delete route
Route::delete('/applicant/documents/{document}', [App\Http\Controllers\ApplicantProfileController::class, 'deleteDocument'])->name('applicant.documents.delete');

// Fix documents route - pass documents collection
Route::get('/applicant/documents', function () {
    $applicant = Auth::user()->applicant;
    if (!$applicant) return redirect()->route('applicant.profile.create');
    $documents = $applicant->documents()->latest()->get();
    return view('applicant.documents', compact('documents'));
})->name('applicant.documents');

// Google OAuth Routes
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])->name('google.callback');

// Google OAuth Debug
Route::get('/auth/google-debug', function () { return view('auth.google-test'); })->name('google.debug');
