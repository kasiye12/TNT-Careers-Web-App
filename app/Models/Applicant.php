<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'user_id',
        'first_name_am', 'father_name_am', 'grand_father_name_am',
        'first_name_en', 'father_name_en', 'grand_father_name_en',
        'gender', 'dob', 'nationality',
        'national_id_number', 'passport_number',
        'region', 'zone_subcity', 'woreda', 'kebele_house_no', 'city',
        'has_construction_exp', 'total_years_exp',
        'profile_completed',
        'skills', 'languages', 'certifications',
        'linkedin_url', 'professional_title',
    ];

    protected $casts = [
        'dob' => 'date',
        'has_construction_exp' => 'boolean',
        'profile_completed' => 'boolean',
        'total_years_exp' => 'decimal:1',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function educationHistories() { return $this->hasMany(EducationHistory::class); }
    public function workExperiences() { return $this->hasMany(WorkExperience::class); }
    public function documents() { return $this->hasMany(Document::class); }
    public function applications() { return $this->hasMany(Application::class); }

    public function getFullNameAmAttribute(): string
    {
        return trim("{$this->first_name_am} {$this->father_name_am} {$this->grand_father_name_am}");
    }

    public function getFullNameEnAttribute(): string
    {
        return trim("{$this->first_name_en} {$this->father_name_en} {$this->grand_father_name_en}");
    }

    public function calculateTotalExperience(): float
    {
        $totalYears = 0;
        foreach ($this->workExperiences as $exp) {
            $startDate = $exp->from_date;
            $endDate = $exp->is_current ? now() : ($exp->to_date ?? now());
            $totalYears += $startDate->diffInYears($endDate);
        }
        return round($totalYears, 1);
    }

    public function hasConstructionExperience(): bool
    {
        return $this->workExperiences()->where('is_construction_company', true)->exists();
    }
}
