<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuChilds extends Model
{
    protected $table = 'menu_childs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'menu_id ',
        'menu_parent_id',
        'menu_type',
        'item_label',
        'custom_url',
    ];
}
