<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    protected $table = 'media';
    
    protected $fillable = [
        'model_type', 'model_id', 'uuid', 'collection_name', 'name', 'file_name',
        'mime_type', 'disk', 'conversions_disk', 'size', 'manipulations',
        'custom_properties', 'generated_conversions', 'responsive_images', 'order_column'
    ];
    
    protected $casts = [
        'manipulations' => 'array',
        'custom_properties' => 'array',
        'generated_conversions' => 'array',
        'responsive_images' => 'array',
    ];
    
    public function model(): MorphTo
    {
        return $this->morphTo();
    }
    
    public function getUrlAttribute()
    {
        return asset('storage/media/' . $this->file_name);
    }
    
    public function getThumbnailUrlAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($extension), $imageExtensions)) {
            return asset('storage/media/thumbnails/' . $this->file_name);
        }
        
        // Return icon based on file type
        return $this->getFileIcon();
    }
    
    protected function getFileIcon()
    {
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);
        
        $icons = [
            'pdf' => 'pdf-icon.png',
            'doc' => 'word-icon.png',
            'docx' => 'word-icon.png',
            'xls' => 'excel-icon.png',
            'xlsx' => 'excel-icon.png',
            'ppt' => 'powerpoint-icon.png',
            'pptx' => 'powerpoint-icon.png',
            'zip' => 'archive-icon.png',
            'rar' => 'archive-icon.png',
            'mp3' => 'audio-icon.png',
            'wav' => 'audio-icon.png',
            'mp4' => 'video-icon.png',
            'avi' => 'video-icon.png',
        ];
        
        $icon = $icons[strtolower($extension)] ?? 'file-icon.png';
        return asset('assets/icons/' . $icon);
    }
}