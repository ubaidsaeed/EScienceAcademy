<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $table = 'boards';
    
    protected $fillable = [
        'name',
        'slug',
        'content',
        'image_url',
        'status'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['image_url_full'];
    
    /**
     * Generate slug before saving
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($board) {
            $board->slug = \Illuminate\Support\Str::slug($board->name);
        });
        
        static::updating(function ($board) {
            if ($board->isDirty('name')) {
                $board->slug = \Illuminate\Support\Str::slug($board->name);
            }
        });
    }
    
    /**
     * Get the full image URL
     */
    public function getImageUrlFullAttribute()
    {
        if ($this->image_url) {
            return asset('storage/app/public/board/' . $this->image_url);
        }
        return null;
    }
    
    /**
     * Get the original image URL for display
     */
    public function getImageUrlAttribute($value)
    {
        if ($value) {
            return asset('storage/app/public/board/' . $value);
        }
        return null;
    }
    
    /**
     * Scope active boards
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Scope inactive boards
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
    
    /**
     * Get parent board
     */
    public function parent()
    {
        return $this->belongsTo(Board::class, 'parent_id');
    }
    
    /**
     * Get child boards
     */
    public function children()
    {
        return $this->hasMany(Board::class, 'parent_id');
    }
}