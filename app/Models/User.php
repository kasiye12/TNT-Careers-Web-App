<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, Notifiable;
    // Removed: implements MustVerifyEmail

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'status',
        'email_verified_at',
        'notification_preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function applicant()
    {
        return $this->hasOne(Applicant::class);
    }

    public function isAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isHRManager(): bool
    {
        return $this->user_type === 'hr_manager';
    }

    public function isEvaluator(): bool
    {
        return $this->user_type === 'evaluator';
    }

    public function isApplicant(): bool
    {
        return $this->user_type === 'applicant';
    }
}
