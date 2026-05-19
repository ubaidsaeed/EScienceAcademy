<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $fillable = [
        'footer_quick_links',
        'header_menus',
        'social_links',
        'whatsapp_contact',
        'address',
        'site_title',
        'site_description',
        'site_logo',
        'favicon',
        'contact_mail',
    ];

    protected $casts = [
        'footer_quick_links' => 'array',
        'header_menus' => 'array',
        'social_links' => 'array',
    ];
}
