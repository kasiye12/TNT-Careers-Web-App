<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'vacancy_id',
        'applicant_id',
        'status',
        'declaration_accepted',
        'rejection_reason',
        'auto_screening_results',
        'submitted_at',
    ];

    protected $casts = [
        'declaration_accepted' => 'boolean',
        'submitted_at' => 'datetime',
        'auto_screening_results' => 'array',
    ];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function evaluationScores()
    {
        return $this->hasMany(EvaluationScore::class);
    }

    public function offerLetter()
    {
        return $this->hasOne(OfferLetter::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function isEligibleForShortlisting(): bool
    {
        $vacancy = $this->vacancy;
        $applicant = $this->applicant;

        if ($applicant->total_years_exp < $vacancy->min_years_experience) {
            return false;
        }

        if ($vacancy->construction_experience_required && !$applicant->hasConstructionExperience()) {
            return false;
        }

        return true;
    }
}
