<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomeQuery extends Model
{
    use HasFactory;

    protected $table = 'custom_query';

    protected $fillable = [
        'name',
        'email',
        'board',
        'level',
        'subject',
        'message',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}