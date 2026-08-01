<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    protected $fillable = [
        'applicant_id',
        'organization_name',
        'sector',
        'construction_grade',
        'is_construction_company',
        'position_held',
        'key_responsibilities',
        'project_type',
        'project_cost_etb',
        'from_date',
        'to_date',
        'is_current',
        'reason_for_leaving',
        'experience_letter_path',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'is_construction_company' => 'boolean',
        'is_current' => 'boolean',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function getDurationAttribute(): string
    {
        $startDate = $this->from_date;
        $endDate = $this->is_current ? now() : ($this->to_date ?? now());
        
        $diff = $startDate->diff($endDate);
        
        if ($diff->y > 0) {
            return "{$diff->y} year(s) {$diff->m} month(s)";
        }
        
        return "{$diff->m} month(s)";
    }
}
