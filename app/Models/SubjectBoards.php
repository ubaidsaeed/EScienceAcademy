<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectBoards extends Model
{
     protected $table ='subject_boards';
     protected $fillable = ['subject_id', 'board_id','level_id'];
     
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
