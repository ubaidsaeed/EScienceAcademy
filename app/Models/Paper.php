<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $table ='papers';

    protected $fillable = [
        'id',
        'user_id',
        'exam_id',
        'exam_schedule_id',
        'short_question_mark',
        'long_question_mark',
        'mcqs_question_mark',
        'scqs_question_mark',
        'total_mark',
        'exam_total_mark',
        'grade',
        'status',
        'created_at',
        'updated_at'
    ];
}
