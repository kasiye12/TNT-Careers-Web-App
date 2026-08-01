<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'interview_type',
        'scheduled_at',
        'end_time',
        'venue',
        'instructions',
        'status',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function panelMembers()
    {
        return $this->hasMany(InterviewPanel::class);
    }
}
