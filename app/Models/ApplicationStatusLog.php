<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationStatusLog extends Model
{
    protected $fillable = [
        'application_id',
        'changed_by',
        'old_status',
        'new_status',
        'notes',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
