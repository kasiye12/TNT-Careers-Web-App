<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewPanel extends Model
{
    protected $fillable = [
        'interview_id',
        'user_id',
        'role',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
