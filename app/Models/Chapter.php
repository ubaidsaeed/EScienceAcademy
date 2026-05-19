<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $table = 'chapters';
    protected $fillable = [
        'id',
        'title',
        'slug',
        'subject_id',
        'content',
        'board_id',
        'level_id',
        'sequence_order',
        'file'
    ];
}
