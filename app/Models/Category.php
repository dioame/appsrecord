<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
    ];

    public function appListings(): HasMany
    {
        return $this->hasMany(AppListing::class);
    }

    public function publishedApps(): HasMany
    {
        return $this->hasMany(AppListing::class)->where('is_published', true);
    }
}
