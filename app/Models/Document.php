<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'applicant_id',
        'document_type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'verified',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
