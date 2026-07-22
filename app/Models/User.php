<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'google_id',
        'avatar',
        'bio',
        'website',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (! filled($user->slug)) {
                $user->slug = static::generateUniqueSlug($user->name ?? 'creator');
            }
        });

        static::updating(function (User $user) {
            if ($user->isDirty('name') && ! $user->isDirty('slug') && ! filled($user->getOriginal('slug'))) {
                $user->slug = static::generateUniqueSlug($user->name, $user->id);
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'creator';
        $slug = $base;
        $i = 2;

        while (
            static::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function hasPassword(): bool
    {
        return filled($this->password);
    }

    public function ensureSlug(): string
    {
        if (! filled($this->slug)) {
            $this->forceFill([
                'slug' => static::generateUniqueSlug($this->name, $this->id),
            ])->saveQuietly();
        }

        return $this->slug;
    }

    public function publicUrl(): string
    {
        return route('creators.show', $this->ensureSlug());
    }

    public function websiteUrl(): ?string
    {
        if (! filled($this->website)) {
            return null;
        }

        $url = trim($this->website);

        if (! preg_match('#^https?://#i', $url)) {
            $url = 'https://'.$url;
        }

        return $url;
    }

    public function websiteHost(): ?string
    {
        $url = $this->websiteUrl();

        if (! $url) {
            return null;
        }

        return preg_replace('#^www\.#i', '', parse_url($url, PHP_URL_HOST) ?: $url);
    }

    public function publishedApps(): HasMany
    {
        return $this->appListings()->where('is_published', true);
    }

    public function appListings(): HasMany
    {
        return $this->hasMany(AppListing::class);
    }

    public function appRatings(): HasMany
    {
        return $this->hasMany(AppRating::class);
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', trim($this->name)) ?: [];

        return collect($words)
            ->filter()
            ->take(2)
            ->map(fn (string $word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('') ?: 'A';
    }
}
