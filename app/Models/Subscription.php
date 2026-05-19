<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'plan_price',
        'start_date',
        'end_date',
        'board_id',
        'level_id',
        'user_id',
        'payment_method_id',
        'payment_status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'plan_price' => 'decimal:2'
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}