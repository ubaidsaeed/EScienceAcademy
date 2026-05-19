<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'id',
        'title',
        'slug',
        'content',
        'thumbnail',
        'created_at',
        'status',
    ];
    
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function submenus()
    {
        return $this->hasMany(SubMenu::class);
    }
}
