<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MultiQuestions extends Model
{

    protected $table = 'multi_questions';

    protected $fillable = [
        'id',
        'option',
        'question_id',
        'correct_answer',
        'question_type',
        'created_at',
    ];
}
