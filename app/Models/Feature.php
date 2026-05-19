<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = ['key', 'name', 'has_content', 'icon', 'sort_order', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function subjectContents()
    {
        return $this->hasMany(SubjectContent::class);
    }
    public function subscriptionPlans()
    {
        return $this->belongsToMany(SubscriptionPlan::class, 'subscription_plan_feature');
    }
}