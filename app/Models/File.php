<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'original_name',
        'slug',
        'description',
        'folder_id',
        'subject_id',
        'board_id',
        'level_id',
        'path',
        'size',
        'mime_type',
        'is_vimeo',
        'vimeo_id',
        'vimeo_url',
        'order',
        'download_count',
        'view_count',
        'status'
    ];

    protected $casts = [
        'is_vimeo' => 'boolean',
        'size' => 'integer',
        'download_count' => 'integer',
        'view_count' => 'integer',
        'status' => 'boolean'
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
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

    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'package_file_access')
                    ->withTimestamps();
    }
}