<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipRequestAchievements extends Model
{
    
    protected $table = 'scholarship_request_achievements';
    
    protected $fillable = [
        'scholarship_requests_id',
        'achievement',
    ];
        
    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(ScholarshipRequests::class);
    }
}


