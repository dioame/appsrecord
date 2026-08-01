<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class AppListing extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'platform',
        'name',
        'author',
        'sub_authors',
        'slug',
        'description',
        'link',
        'logo',
        'images',
        'is_published',
        'approval_status',
    ];

    public const PLATFORMS = ['mobile', 'web', 'desktop', 'others'];

    public const PLATFORM_LABELS = [
        'mobile' => 'Mobile',
        'web' => 'Web',
        'desktop' => 'Desktop',
        'others' => 'Others',
    ];

    public const APPROVAL_PENDING = 'pending';

    public const APPROVAL_APPROVED = 'approved';

    public const APPROVAL_REJECTED = 'rejected';

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'sub_authors' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @param  Builder<AppListing>  $query
     * @return Builder<AppListing>
     */
    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where('approval_status', self::APPROVAL_APPROVED);
    }

    /**
     * @param  Builder<AppListing>  $query
     * @return Builder<AppListing>
     */
    public function scopePendingApproval(Builder $query): Builder
    {
        return $query
            ->where('is_published', true)
            ->where('approval_status', self::APPROVAL_PENDING);
    }

    public function isPendingApproval(): bool
    {
        return $this->is_published && $this->approval_status === self::APPROVAL_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === self::APPROVAL_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->approval_status === self::APPROVAL_REJECTED;
    }

    public function isLive(): bool
    {
        return $this->is_published && $this->isApproved();
    }

    public function approvalLabel(): string
    {
        return match ($this->approval_status) {
            self::APPROVAL_PENDING => 'Pending approval',
            self::APPROVAL_REJECTED => 'Rejected',
            default => 'Approved',
        };
    }

    public static function approvalFor(User $user, bool $wantsPublish): string
    {
        if (! $wantsPublish) {
            return self::APPROVAL_APPROVED;
        }

        return $user->canPublishWithoutApproval()
            ? self::APPROVAL_APPROVED
            : self::APPROVAL_PENDING;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(AppRating::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->orderBy('name');
    }

    /**
     * @param  Builder<AppListing>  $query
     * @param  list<string>  $slugs
     * @return Builder<AppListing>
     */
    public function scopeWithTagSlugs(Builder $query, array $slugs): Builder
    {
        $slugs = array_values(array_filter($slugs));

        if ($slugs === []) {
            return $query;
        }

        foreach ($slugs as $slug) {
            $query->whereHas('tags', fn (Builder $tags) => $tags->where('slug', $slug));
        }

        return $query;
    }

    public function averageRating(): float
    {
        if (array_key_exists('ratings_avg_rating', $this->attributes) && $this->attributes['ratings_avg_rating'] !== null) {
            return round((float) $this->attributes['ratings_avg_rating'], 1);
        }

        return round((float) ($this->ratings()->avg('rating') ?? 0), 1);
    }

    public function ratingsCount(): int
    {
        if (array_key_exists('ratings_count', $this->attributes)) {
            return (int) $this->attributes['ratings_count'];
        }

        return $this->ratings()->count();
    }

    public function ratingFor(?User $user): ?int
    {
        if (! $user) {
            return null;
        }

        return $this->ratings()->where('user_id', $user->id)->value('rating');
    }

    public function platformLabel(): string
    {
        return self::PLATFORM_LABELS[$this->platform] ?? self::PLATFORM_LABELS['mobile'];
    }

    public function authorName(): string
    {
        return filled($this->author) ? $this->author : ($this->user->name ?? 'Unknown');
    }

    /**
     * @return list<array{name: string, email: ?string}>
     */
    public function subAuthorEntries(): array
    {
        return collect($this->sub_authors ?? [])
            ->filter(fn ($entry) => is_array($entry))
            ->map(function (array $entry) {
                $name = trim((string) ($entry['name'] ?? ''));
                $email = trim((string) ($entry['email'] ?? ''));

                if ($name === '') {
                    return null;
                }

                return [
                    'name' => $name,
                    'email' => $email !== '' ? $email : null,
                ];
            })
            ->filter()
            ->values()
            ->all();
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

    public function isOthers(): bool
    {
        return $this->platform === 'others';
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
