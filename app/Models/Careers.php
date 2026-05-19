<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Careers extends Model
{
    protected $table  = 'careers';

    protected $fillable = [
        'name',
        'policy',
        'email',
        'position',
        'contact',
        'cover_letter',
        'resume',
        'created_at',
        'updated_at'
    ];
}
