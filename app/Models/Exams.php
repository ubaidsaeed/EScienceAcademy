<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{

    protected $table = 'exams';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'date',
        'notes',
    ];
}
