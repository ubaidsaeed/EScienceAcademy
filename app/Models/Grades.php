<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grades extends Model
{

    protected $table = 'grades';
    protected $fillable = [
        'id',
        'name',
        'point',
        'mark_from',
        'mark_to',
        'notes',
        'slug',
    ];
}
