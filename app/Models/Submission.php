<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{

    protected $table = 'submissions';

    protected $fillable = [
        'id',
        'user_id',
        'chapter',
        'assignment_id',
        'file',
        'notes',
        'created_at',
    ];
}
