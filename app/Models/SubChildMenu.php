<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubChildMenu extends Model
{
    protected $table ='sub_child_menus';
    protected $fillable = [
        'name', 'slug', 'page_id', 'priority', 'link', 
        'status', 'target_window', 'menu_id', 'menu_child_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function submenu()
    {
        return $this->belongsTo(SubMenu::class, 'menu_child_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badgeClass = $this->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
        return '<span class="'.$badgeClass.'">'.ucfirst($this->status).'</span>';
    }
}