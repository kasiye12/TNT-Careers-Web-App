<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicantProfileController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OfferLetterController;
use App\Http\Controllers\CVGeneratorController;
use App\Http\Controllers\ResumeBuilderController;
use App\Http\Controllers\SalaryCalculatorController;
use App\Http\Controllers\JobAlertController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SystemController;

// ============================================
// PUBLIC ROUTES
// ============================================
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/vacancies', [VacancyController::class, 'publicIndex'])->name('vacancies.public.index');
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'publicShow'])->name('vacancies.public.show');
Route::get('/api/v1/vacancies/latest', [VacancyController::class, 'apiLatest'])->name('api.vacancies.latest');

// ============================================
// AUTHENTICATION ROUTES
// ============================================
require __DIR__.'/auth.php';

// Google OAuth
Route::get('/auth/google', [App\Http\Controllers\Auth\GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [App\Http\Controllers\Auth\GoogleAuthController::class, 'callback'])->name('google.callback');

// ============================================
// APPLICANT ROUTES
// ============================================
Route::middleware(['auth'])->prefix('applicant')->name('applicant.')->group(function () {
    Route::get('/dashboard', function () { return view('applicant.dashboard'); })->name('dashboard');
    Route::get('/profile/create', [ApplicantProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile', [ApplicantProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit', [ApplicantProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ApplicantProfileController::class, 'update'])->name('profile.update');
    Route::get('/skills', [ApplicantProfileController::class, 'editSkills'])->name('skills.edit');
    Route::put('/skills', [ApplicantProfileController::class, 'updateSkills'])->name('skills.update');
    Route::get('/education/create', [ApplicantProfileController::class, 'addEducation'])->name('education.create');
    Route::post('/education', [ApplicantProfileController::class, 'storeEducation'])->name('education.store');
    Route::get('/education/{education}/edit', [ApplicantProfileController::class, 'editEducation'])->name('education.edit');
    Route::put('/education/{education}', [ApplicantProfileController::class, 'updateEducation'])->name('education.update');
    Route::delete('/education/{education}', [ApplicantProfileController::class, 'deleteEducation'])->name('education.delete');
    Route::get('/experience/create', [ApplicantProfileController::class, 'addExperience'])->name('experience.create');
    Route::post('/experience', [ApplicantProfileController::class, 'storeExperience'])->name('experience.store');
    Route::get('/experience/{experience}/edit', [ApplicantProfileController::class, 'editExperience'])->name('experience.edit');
    Route::put('/experience/{experience}', [ApplicantProfileController::class, 'updateExperience'])->name('experience.update');
    Route::delete('/experience/{experience}', [ApplicantProfileController::class, 'deleteExperience'])->name('experience.delete');
    Route::get('/documents', function () {
        $applicant = Auth::user()->applicant;
        $documents = $applicant ? $applicant->documents()->latest()->get() : collect();
        return view('applicant.documents', compact('documents'));
    })->name('documents');
    Route::post('/documents', [ApplicantProfileController::class, 'uploadDocument'])->name('documents.upload');
    Route::get('/documents/{document}/download', [ApplicantProfileController::class, 'downloadDocument'])->name('documents.download');
    Route::delete('/documents/{document}', [ApplicantProfileController::class, 'deleteDocument'])->name('documents.delete');
    Route::post('/profile/complete', [ApplicantProfileController::class, 'completeProfile'])->name('profile.complete');
    Route::get('/vacancies/{vacancy}/apply', [ApplicationController::class, 'apply'])->name('apply');
    Route::post('/vacancies/{vacancy}/apply', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/applications', [ApplicationController::class, 'myApplications'])->name('applications');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
});

// ============================================
// HR & ADMIN ROUTES
// ============================================
Route::middleware(['auth'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', function () { return view('hr.dashboard'); })->name('dashboard');
    Route::resource('vacancies', VacancyController::class);
    Route::post('/vacancies/{vacancy}/publish', [VacancyController::class, 'publish'])->name('vacancies.publish');
    Route::post('/vacancies/{vacancy}/close', [VacancyController::class, 'close'])->name('vacancies.close');
    Route::get('/applications/review', [ApplicationController::class, 'reviewIndex'])->name('applications.review');
    Route::get('/applications/shortlisted', [ApplicationController::class, 'shortlistedCandidates'])->name('applications.shortlisted');
    Route::get('/applications/pipeline', [ApplicationController::class, 'pipeline'])->name('applications.pipeline');
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
    Route::get('/reports', function () { return view('reports.index'); })->name('reports.index');
    Route::get('/reports/vacancy-progress', [ReportController::class, 'vacancyProgress'])->name('reports.vacancy-progress');
    Route::get('/reports/demographics', [ReportController::class, 'genderDemographics'])->name('reports.demographics');
    Route::get('/reports/analytics', function () { return view('hr.reports.analytics'); })->name('hr.reports.analytics');
    Route::get('/reports/pipeline-summary', function () { return view('reports.pipeline-summary'); })->name('hr.reports.pipeline-summary');
    Route::post('/reports/export-applications', [ReportController::class, 'exportApplications'])->name('reports.export-applications');
    Route::post('/reports/export-demographics', [ReportController::class, 'exportDemographics'])->name('reports.export-demographics');
    Route::post('/reports/shortlist-matrix-pdf', [ReportController::class, 'shortlistMatrixPDF'])->name('reports.shortlist-matrix-pdf');
});

// ============================================
// EVALUATOR ROUTES
// ============================================
Route::middleware(['auth'])->group(function () {
    Route::get('/evaluator/dashboard', function () { return view('evaluator.dashboard'); })->name('evaluator.dashboard');
    Route::get('/evaluator/summary', function () { return view('evaluator.summary'); })->name('evaluator.summary');
    Route::get('/evaluations/{application}/scorecard', [EvaluationController::class, 'getScorecard'])->name('evaluations.scorecard');
    Route::post('/evaluations/{application}', [EvaluationController::class, 'scoreApplication'])->name('evaluations.score');
});

// ============================================
// ADMIN ROUTES
// ============================================
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [App\Http\Controllers\Admin\UserManagementController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    
    // Settings - only main page is GET
    Route::get('/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
    Route::put('/settings/general', [App\Http\Controllers\Admin\SettingsController::class, 'updateGeneral'])->name('settings.update-general');
    Route::put('/settings/recruitment', [App\Http\Controllers\Admin\SettingsController::class, 'updateRecruitment'])->name('settings.update-recruitment');
    Route::put('/settings/email', [App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('settings.update-email');
    Route::put('/settings/documents', [App\Http\Controllers\Admin\SettingsController::class, 'updateDocuments'])->name('settings.update-documents');
    Route::post('/settings/test-email', [App\Http\Controllers\Admin\SettingsController::class, 'testEmail'])->name('settings.test-email');
    
    Route::get('/activity', function () { return view('admin.activity'); })->name('activity');
});

// ============================================
// CAREER TOOLS ROUTES
// ============================================
Route::get('/cv-generator', [CVGeneratorController::class, 'index'])->name('cv.generator');
Route::post('/cv-generator', [CVGeneratorController::class, 'generate'])->name('cv.generate');
Route::get('/resume-builder', [ResumeBuilderController::class, 'index'])->name('resume.builder');
Route::post('/resume-builder', [ResumeBuilderController::class, 'generate'])->name('resume.generate');
Route::get('/salary-calculator', [SalaryCalculatorController::class, 'index'])->name('salary.calculator');
Route::post('/salary-calculate', [SalaryCalculatorController::class, 'calculate'])->name('salary.calculate');
Route::get('/interview-tips', function () { return view('tools.interview-tips'); })->name('interview.tips');
Route::get('/job-alerts', [JobAlertController::class, 'index'])->name('job.alerts');
Route::post('/job-alerts', [JobAlertController::class, 'subscribe'])->name('job.alerts.subscribe');

// ============================================
// ACCOUNT ROUTES
// ============================================
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/settings', [AccountController::class, 'settings'])->name('settings');
    Route::put('/email', [AccountController::class, 'updateEmail'])->name('update-email');
    Route::put('/phone', [AccountController::class, 'updatePhone'])->name('update-phone');
    Route::put('/password', [AccountController::class, 'updatePassword'])->name('update-password');
    Route::delete('/delete', [AccountController::class, 'deleteAccount'])->name('delete');
});

// ============================================
// SYSTEM ROUTES
// ============================================
Route::get('/system', function () { return view('system.overview'); })->name('system.overview');
Route::get('/system/health', [SystemController::class, 'health'])->name('system.health');
Route::get('/api-docs', function () { return view('api-docs'); })->name('api.docs');
Route::get('/search', [SearchController::class, 'global'])->name('search');

// Offer letter public
Route::get('/offer-letters/{offerLetter}/view', [OfferLetterController::class, 'view'])->name('offer-letters.view');
Route::post('/offer-letters/{offerLetter}/respond', [OfferLetterController::class, 'respond'])->name('offer-letters.respond');
