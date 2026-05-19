<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    protected $table = 'questions';

    protected $fillable = [
        'id',
        'board_id',
        'level_id',
        'subject_id',
        'chapter_id',
        'group_id',
        'question_type',
        'marks',
        'shuffle_answer',
        'image_url',
        'question',
        'explanation',
        'created_at',
    ];
}
