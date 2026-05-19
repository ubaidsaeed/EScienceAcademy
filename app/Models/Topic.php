<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{

    protected $table = 'topics';
    protected $fillable = [
        'id',
        'title',
        'slug',
        'subject_id',
        'board_id',
        'level_id',
        'user_id',
        'chapter_id',
        'status',
        'sequence_order',
        'content',
        'file'
    ];
}
