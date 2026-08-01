<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    protected $fillable = [
        'application_id',
        'evaluator_id',
        'evaluation_type',
        'score',
        'max_score',
        'weight_percentage',
        'comments',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'weight_percentage' => 'decimal:2',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
