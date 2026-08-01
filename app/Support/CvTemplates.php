<?php

namespace App\Support;

use App\Models\AppListing;
use App\Models\User;
use Illuminate\Support\Collection;

class CvTemplates
{
    public const CLASSIC = 'classic';

    public const EXECUTIVE = 'executive';

    public const SHOWCASE = 'showcase';

    /**
     * @return array<string, array{label: string, blurb: string}>
     */
    public static function all(): array
    {
        return [
            self::CLASSIC => [
                'label' => 'Classic',
                'blurb' => 'Clean single-column CV with photo, skills, and deployed apps.',
            ],
            self::EXECUTIVE => [
                'label' => 'Executive',
                'blurb' => 'Navy sidebar layout — contact & skills beside experience and apps.',
            ],
            self::SHOWCASE => [
                'label' => 'Showcase',
                'blurb' => 'Portfolio-first layout — deployed apps listed prominently with CV details.',
            ],
        ];
    }

    public static function isValid(?string $template): bool
    {
        return is_string($template) && array_key_exists($template, self::all());
    }

    public static function resolve(?string $template): string
    {
        return self::isValid($template) ? $template : self::CLASSIC;
    }

    public static function view(string $template): string
    {
        return 'creators.cv.templates.'.self::resolve($template);
    }

    /**
     * @param  Collection<int, AppListing>  $apps
     * @return array{avatar: ?string}
     */
    public static function media(User $creator, Collection $apps): array
    {
        return [
            'avatar' => filled($creator->avatar) && preg_match('#^https?://#i', $creator->avatar)
                ? PdfImage::fromUrl($creator->avatar, 320)
                : PdfImage::fromPublicDisk($creator->hasStoredAvatar() ? $creator->avatar : null, 320),
        ];
    }
}
