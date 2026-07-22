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
        'headline',
        'location',
        'skills',
        'experience',
        'education',
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
            'skills' => 'array',
            'experience' => 'array',
            'education' => 'array',
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

    /**
     * @return list<string>
     */
    public function skillList(): array
    {
        return collect($this->skills ?? [])
            ->map(fn ($skill) => is_string($skill) ? trim($skill) : '')
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<array{title?: string, company?: string, period?: string, description?: string}>
     */
    public function experienceEntries(): array
    {
        return $this->normalizedCvEntries($this->experience);
    }

    /**
     * @return list<array{school?: string, degree?: string, period?: string, description?: string}>
     */
    public function educationEntries(): array
    {
        return $this->normalizedCvEntries($this->education);
    }

    public function hasCvContent(): bool
    {
        return filled($this->headline)
            || filled($this->location)
            || filled($this->bio)
            || count($this->skillList()) > 0
            || count($this->experienceEntries()) > 0
            || count($this->educationEntries()) > 0;
    }

    /**
     * @param  mixed  $entries
     * @return list<array<string, string>>
     */
    protected function normalizedCvEntries(mixed $entries): array
    {
        return collect(is_array($entries) ? $entries : [])
            ->filter(fn ($entry) => is_array($entry))
            ->map(function (array $entry) {
                return collect($entry)
                    ->map(fn ($value) => is_string($value) ? trim($value) : '')
                    ->filter(fn ($value, $key) => is_string($key) && $value !== '')
                    ->all();
            })
            ->filter(fn (array $entry) => $entry !== [])
            ->values()
            ->all();
    }
}
