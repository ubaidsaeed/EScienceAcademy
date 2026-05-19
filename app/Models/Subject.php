<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'board_id',
        'level_id',
        'slug',
        'image_url',
        'status',
        'base_price'
    ];

    public function board(): BelongsTo
    {
        return $this->belongsTo(Board::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
  public function subjectBoards()
    {
        return $this->hasMany(SubjectBoards::class);
    }
    
    // Or if using many-to-many:
    public function boards()
    {
        return $this->belongsToMany(Board::class, 'subject_boards')
                    ->withPivot('level_id')
                    ->withTimestamps();
    }
    
    public function levels()
    {
        return $this->belongsToMany(Level::class, 'subject_boards')
                    ->withPivot('board_id')
                    ->withTimestamps();
    }
    

    // Add folder relationship
   public function folders()
{
    return $this->hasMany(Folder::class);
}

public function files()
{
    return $this->hasMany(File::class);
}

public function subscriptionPlans()
{
    return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_subject')
                ->withPivot('subject_price')
                ->withTimestamps();
}
}