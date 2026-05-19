<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudyBoard extends Model
{
    use HasFactory;

    protected $table = 'case_study_board';

    protected $fillable = [
        'case_id',
        'board_id',
    ];
}