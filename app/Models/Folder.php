<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'path',
        'subject_id',
        'board_id',
        'level_id',
        'order',
        'status',
        'is_chapter' // New field to identify if folder is a chapter
    ];

    protected $casts = [
        'is_chapter' => 'boolean',
        'status' => 'boolean'
    ];

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id')->orderBy('created_at');
    }

    public function chapterChildren()
    {
        return $this->hasMany(Folder::class, 'parent_id')
                    ->where('is_chapter', true)
                    ->orderBy('created_at');
    }

    public function files()
    {
        return $this->hasMany(File::class)->orderBy('created_at');
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

    // Scope to get only chapters
    public function scopeChapters($query)
    {
        return $query->where('is_chapter', true);
    }

    // Get all descendant chapters
    public function getDescendantChapters()
    {
        $chapters = collect();

        if ($this->is_chapter) {
            $chapters->push($this);
        }

        foreach ($this->children as $child) {
            $chapters = $chapters->merge($child->getDescendantChapters());
        }

        return $chapters;
    }
    public function scopeForPackage($query, $packageId)
{
    $package = SubscriptionPlan::with('features')->find($packageId);

    if (!$package) {
        return $query;
    }

    // Get feature keys from the package
    $featureKeys = $package->features->pluck('key')->toArray();

    if (empty($featureKeys)) {
        return $query;
    }

    // Check if we should filter by feature-based folders
    // This assumes folder names or paths contain feature indicators
    return $query->where(function($query) use ($featureKeys) {
        foreach ($featureKeys as $featureKey) {
            $query->orWhere('name', 'like', "%{$featureKey}%")
                  ->orWhere('path', 'like', "%{$featureKey}%");
        }
    });
}
}
