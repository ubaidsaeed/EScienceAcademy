<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscription_notification extends Model
{
    use HasFactory;

    protected  $table = 'subscription_notification';

    protected $fillable = [
        'id',
        'notification_id',
        'subscription_plan_id'
        ];
}
