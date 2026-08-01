<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function appListings(): BelongsToMany
    {
        return $this->belongsToMany(AppListing::class);
    }

    public function publishedApps(): BelongsToMany
    {
        return $this->belongsToMany(AppListing::class)
            ->publiclyVisible();
    }

    /**
     * @param  list<string>  $names
     * @return Collection<int, Tag>
     */
    public static function findOrCreateFromNames(array $names): Collection
    {
        $ids = [];

        foreach ($names as $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }

            $slug = Str::slug($name);
            if ($slug === '') {
                continue;
            }

            $tag = static::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            );

            $ids[] = $tag->id;
        }

        if ($ids === []) {
            return new Collection;
        }

        return static::query()->whereIn('id', array_unique($ids))->get();
    }
}
