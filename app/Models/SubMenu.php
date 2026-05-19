<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    protected $table = 'submenu'; // Make sure this matches your table name

    protected $fillable = [
        'name', 
        'slug', 
        'page_id', 
        'priority', 
        'link', 
        'status', 
        'target_window', 
        'menu_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function subChildMenus()
    {
        return $this->hasMany(SubChildMenu::class, 'menu_child_id', 'id');
    }

    public function getStatusBadgeAttribute()
    {
        $badgeClass = $this->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
        return '<span class="'.$badgeClass.'">'.ucfirst($this->status).'</span>';
    }

    public function getTargetWindowBadgeAttribute()
    {
        $badgeClass = $this->target_window === '_self' ? 'badge bg-info' : 'badge bg-warning';
        $text = $this->target_window === '_self' ? 'Self' : 'New Tab';
        return '<span class="'.$badgeClass.'">'.$text.'</span>';
    }
}