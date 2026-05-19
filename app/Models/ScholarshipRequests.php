<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Model;

class ScholarshipRequests extends Model
{
    
     protected $table = 'scholarship_requests';

     protected $fillable = [
        'name',
        'policy',
        'father_name',
        'contact_no',
        'salary_slip',
        'email',
        'parents_id_card',
        'electricity_bills',
        'academic_transcripts',
        'parental_bank_certificate',
        'message',
        'created_at',
     ];

     protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function achievements(): HasMany
    {
        return $this->hasMany(ScholarshipRequestAchievements::class);
    }
}

