<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptionplans extends Model
{

    protected $table = 'subscription_plans';

    protected $fillable = [
        'name',
        'price',
        'slug',
        'content',
        'duration',
        'duration_type',
        'student_limit',
        'board_id',
        'level_id',
        'subject_id',
        'status',
        'total_chapters',
        'online_notes',
        'top_past_paper',
        'ws_aw_bg',
        'recorded'
    ];
}
