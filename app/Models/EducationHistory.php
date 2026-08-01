<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationHistory extends Model
{
    protected $fillable = [
        'applicant_id',
        'institution',
        'qualification',
        'field_of_study',
        'cgpa',
        'graduation_year',
        'graduation_year_ec',
        'certificate_file_path',
    ];

    protected $casts = [
        'cgpa' => 'decimal:2',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function getQualificationLabelAttribute(): string
    {
        $labels = [
            'tvet_level_1' => 'TVET Level I',
            'tvet_level_2' => 'TVET Level II',
            'tvet_level_3' => 'TVET Level III',
            'tvet_level_4' => 'TVET Level IV',
            'tvet_level_5' => 'TVET Level V',
            'diploma' => 'Diploma',
            'bsc' => 'BSc Degree',
            'ba' => 'BA Degree',
            'msc' => 'MSc Degree',
            'ma' => 'MA Degree',
            'phd' => 'PhD',
        ];

        return $labels[$this->qualification] ?? $this->qualification;
    }
}
