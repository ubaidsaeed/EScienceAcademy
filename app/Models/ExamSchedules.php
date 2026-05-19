<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedules extends Model
{
    protected $table = 'exam_schedules';
    protected $fillable =
    [
        'id',
        'user_id',
        'board_id',
        'level_id',
        'exam_id',
        'l_q_limit',
        's_q_limit',
        'total_marks',
        'quiz_limit',
        'quiz_id',
        'subject_id',
        'chapter_id',
        'Date',
        'start_time',
        'exam_type',
        'result_after_exam',
        'question_limits',
        'end_time',
        'note',
    ];
}
