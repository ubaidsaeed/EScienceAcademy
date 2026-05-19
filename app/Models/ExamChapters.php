<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamChapters extends Model
{
    protected $table = 'exam_chapters';

    protected $fillable = [
        'id',
        'exam_id',
        'chapter_id',
        'created_at',
        'updated_at',
    ];
}
