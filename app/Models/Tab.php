<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    
    protected $table = 'tabs';
    protected $fillable = [
        'name',
        'slug',
        'board_id',
        'status',
        'content',
        'priority'

    ];
}
