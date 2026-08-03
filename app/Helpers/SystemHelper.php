<?php

namespace App\Helpers;

class SystemHelper
{
    public static function getStats()
    {
        return [
            'vacancies' => \App\Models\Vacancy::count(),
            'applications' => \App\Models\Application::count(),
            'users' => \App\Models\User::count(),
            'shortlisted' => \App\Models\Application::where('status', 'shortlisted')->count(),
            'selected' => \App\Models\Application::where('status', 'selected')->count(),
            'published' => \App\Models\Vacancy::where('status', 'published')->count(),
        ];
    }
    
    public static function formatPhone($phone)
    {
        if (str_starts_with($phone, '0')) {
            return '+251' . substr($phone, 1);
        }
        return $phone;
    }
    
    public static function getStatusColor($status)
    {
        return match($status) {
            'submitted' => 'blue',
            'document_verified' => 'green',
            'shortlisted' => 'yellow',
            'written_exam' => 'purple',
            'interview' => 'orange',
            'medical_check' => 'red',
            'selected' => 'green',
            'rejected' => 'gray',
            default => 'gray',
        };
    }
}
