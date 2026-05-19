<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionGroups extends Model
{

    protected $table = 'question_groups';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'created_at',
   ];
}
