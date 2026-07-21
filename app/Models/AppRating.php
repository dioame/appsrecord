<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppRating extends Model
{
    protected $fillable = [
        'user_id',
        'app_listing_id',
        'rating',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function appListing(): BelongsTo
    {
        return $this->belongsTo(AppListing::class);
    }
}
