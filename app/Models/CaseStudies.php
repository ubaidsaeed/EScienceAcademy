<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudies extends Model
{
    use HasFactory;

    protected $table = 'case_study';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
    ];

    public function boards()
    {
        return $this->belongsToMany(Board::class, 'case_study_board', 'case_id', 'board_id');
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/case-study/' . $this->image);
        }
        return null;
    }
}