<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menu_parents extends Model
{
    protected $table = 'menu_parents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'menu_id',
        'menu_type',
        'child_menu_type',
        'item_id',
        'item_label',
        'custom_url',
    ];

}
