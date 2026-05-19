<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{

    protected $table = 'teachers';
    protected $fillable = [
        'id',
        'name',
        'slug',
        'user_id',
        'gender',
        'national_id',
        'phone',
        'image_url',
        'religion',
        'birth_date',
        'presentaddress',
        'permanentaddress',
        'joining_date',
        'facebook_url',
        'twitter_url',
        'linkedin_url',
        'pinterest_url',
        'youtube_url',
        'instagram_url',
        'other_info',

    ];
}
