<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'board_id',
        'content',
        'slug',
        'image_url',
        'status'
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function subscriptionPlans()
    {
        return $this->hasMany(SubscriptionPlan::class);
    }
}