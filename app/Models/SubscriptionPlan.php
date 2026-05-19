<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $table = 'subscription_plans';
    protected $fillable = [
        'name',
        'slug',
        'content',
        'price',
        'duration',
        'duration_type',
        'student_limit',
        'board_id',
        'level_id',
        'total_chapters',
        'online_notes',
        'top_past_paper',
        'ws_aw_bg',
        'recorded',
        'status'
    ];

    protected $casts = [
        'online_notes' => 'boolean',
        'top_past_paper' => 'boolean',
        'ws_aw_bg' => 'boolean',
        'recorded' => 'boolean',
        'price' => 'decimal:2',
        'total_chapters' => 'integer',
        'status' => 'boolean'
    ];

    protected $appends = ['features_list'];

    // Accessor for features list
    public function getFeaturesListAttribute()
    {
        $features = [];
        if ($this->online_notes) $features[] = 'Online Notes';
        if ($this->top_past_paper) $features[] = 'Topical Past Papers';
        if ($this->ws_aw_bg) $features[] = 'Worksheets with Award Badges';
        if ($this->recorded) $features[] = 'Recorded/Live Sessions';
        
        return !empty($features) ? implode(', ', $features) : 'No Features';
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subscription_plan_subject')
                    ->withPivot(['subject_price', 'chapter_ids'])
                    ->withTimestamps();
    }

   // app/Models/SubscriptionPlan.php
public function accessibleFolders()
{
    return $this->belongsToMany(Folder::class, 'package_folder_access')
                ->withTimestamps();
}

public function accessibleFiles()
{
    return $this->belongsToMany(File::class, 'package_file_access')
                ->withTimestamps();
}

    public function subscriptionChapters()
    {
        return $this->hasMany(SubscriptionChapters::class, 'subscription_id', 'id');
    }

    // Get chapters for a specific subject
    public function getChaptersForSubject($subjectId)
    {
        $subject = $this->subjects()->where('subject_id', $subjectId)->first();
        return $subject ? (json_decode($subject->pivot->chapter_ids, true) ?? []) : [];
    }

    // Get all accessible chapters
    public function getAllAccessibleChapters()
    {
        $chapters = [];
        foreach ($this->subjects as $subject) {
            $subjectChapters = json_decode($subject->pivot->chapter_ids, true) ?? [];
            $chapters = array_merge($chapters, $subjectChapters);
        }
        return array_unique($chapters);
    }
    public function features()
    {
        return $this->belongsToMany(Feature::class, 'subscription_plan_feature');
    }
    // In app/Models/SubscriptionPlan.php
public function users()
{
    return $this->belongsToMany(User::class, 'subscriptions')
                ->withPivot('start_date', 'end_date', 'status');
    // Change table name and columns as per your setup
}
    
}