<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $fillable = [
        'name', 'slug', 'page_id', 'priority', 
        'link', 'status', 'target_window'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function submenus()
    {
        return $this->hasMany(SubMenu::class);
    }

    public function subChildMenus()
    {
        return $this->hasManyThrough(SubChildMenu::class, SubMenu::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badgeClass = $this->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
        return '<span class="'.$badgeClass.'">'.ucfirst($this->status).'</span>';
    }
}