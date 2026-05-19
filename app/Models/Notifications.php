<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $fillable = [
        'id',
        'title',
        'status',
        'content',
        'subscription_plan_id',
        'created_at',
        'updated_at',
    ];
}
