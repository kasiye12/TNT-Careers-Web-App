<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['code', 'name', 'name_am', 'note', 'is_active'];
    
    protected $casts = ['is_active' => 'boolean'];
    
    public function users()
    {
        return $this->hasMany(User::class, 'department', 'name');
    }
}
