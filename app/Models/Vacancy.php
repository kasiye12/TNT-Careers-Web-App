<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vacancy extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'vacancy_number',
        'title',
        'job_category',
        'department',
        'duty_station_category',
        'duty_station',
        'specific_location',
        'employment_type',
        'positions_count',
        'salary_type',
        'salary_amount',
        'salary_currency',
        'min_years_experience',
        'max_years_experience',
        'required_field_of_study',
        'minimum_cgpa',
        'min_education_level',
        'construction_experience_required',
        'min_construction_years',
        'opening_date',
        'closing_date',
        'description_en',
        'description_am',
        'responsibilities_en',
        'responsibilities_am',
        'requirements_en',
        'requirements_am',
        'status',
        'created_by',
    ];

    protected $casts = [
        'opening_date' => 'date',
        'closing_date' => 'date',
        'construction_experience_required' => 'boolean',
        'salary_amount' => 'decimal:2',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('closing_date', '>=', now());
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('job_category', $category);
    }

    public function generateVacancyNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('TNT-VAC-%s-%03d', $year, $count);
    }

    public function getApplicationsCountAttribute(): int
    {
        return $this->applications()->count();
    }

    public function isOpen(): bool
    {
        return $this->status === 'published' && $this->closing_date >= now();
    }
}
