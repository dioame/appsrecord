<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class AppListing extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'platform',
        'name',
        'author',
        'slug',
        'description',
        'link',
        'logo',
        'images',
        'is_published',
    ];

    public const PLATFORMS = ['mobile', 'web', 'desktop'];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function platformLabel(): string
    {
        return match ($this->platform) {
            'web' => 'Web',
            'desktop' => 'Desktop',
            default => 'Mobile',
        };
    }

    public function authorName(): string
    {
        return filled($this->author) ? $this->author : ($this->user->name ?? 'Unknown');
    }

    public function scopeByAuthor($query, string $author)
    {
        $author = trim($author);

        return $query->where(function ($inner) use ($author) {
            $inner->where('author', $author)
                ->orWhere(function ($fallback) use ($author) {
                    $fallback->where(function ($emptyAuthor) {
                        $emptyAuthor->whereNull('author')->orWhere('author', '');
                    })->whereHas('user', fn ($user) => $user->where('name', $author));
                });
        });
    }

    public function isMobile(): bool
    {
        return ($this->platform ?? 'mobile') === 'mobile';
    }

    public function isWeb(): bool
    {
        return $this->platform === 'web';
    }

    public function isDesktop(): bool
    {
        return $this->platform === 'desktop';
    }

    public function logoUrl(): ?string
    {
        return $this->logo ? Storage::disk('public')->url($this->logo) : null;
    }

    public function imageUrls(): array
    {
        return collect($this->images ?? [])
            ->map(fn (string $path) => Storage::disk('public')->url($path))
            ->all();
    }

    public function deleteFiles(): void
    {
        if ($this->logo) {
            Storage::disk('public')->delete($this->logo);
        }

        foreach ($this->images ?? [] as $image) {
            Storage::disk('public')->delete($image);
        }
    }
}
