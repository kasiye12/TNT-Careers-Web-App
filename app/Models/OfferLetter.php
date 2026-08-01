<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    protected $fillable = [
        'application_id',
        'offer_reference_number',
        'position_title',
        'department',
        'duty_station',
        'salary_amount',
        'salary_currency',
        'benefits',
        'reporting_date',
        'offer_expiry_date',
        'status',
        'sent_at',
        'responded_at',
        'response_notes',
        'generated_by',
    ];

    protected $casts = [
        'reporting_date' => 'date',
        'offer_expiry_date' => 'date',
        'sent_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public static function generateReferenceNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        return sprintf('TNT-OFFER-%s-%04d', $year, $count);
    }
}
