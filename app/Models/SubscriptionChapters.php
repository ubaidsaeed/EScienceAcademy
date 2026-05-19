<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionChapters extends Model
{
    use HasFactory;

    protected $table = 'subscription_chapters';
    
    protected $fillable = [
        'subscription_id',
        'chapter_id',
        'subject_id',
        'board_id',
        'level_id'
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}