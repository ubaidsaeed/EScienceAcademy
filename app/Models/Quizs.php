<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quizs extends Model
{

    protected $table = 'quizs';

    protected $fillable =[
        'id',
        'title',
        'instruction',
        'board_id',
        'level_id',
        'group_id',
        'minimum_percentage',
        'random_question',
        'status',
        'created_at',
        'updated_at',
    ];
}
