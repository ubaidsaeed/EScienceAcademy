<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperDetail extends Model
{

    protected $table = 'paper_details';

    protected $fillable = [
        'id',
        'paper_id',
        'question_id',
        'question',
        'answer',
        'question_type',
        'mark',
        'total_mark',
        'created_at',
        'updated_at',
    ];
}
