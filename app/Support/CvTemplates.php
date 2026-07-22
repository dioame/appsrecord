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
                'blurb' => 'Clean single-column CV with photo, skills, and app logos.',
            ],
            self::EXECUTIVE => [
                'label' => 'Executive',
                'blurb' => 'Navy sidebar layout — contact & skills beside experience and apps.',
            ],
            self::SHOWCASE => [
                'label' => 'Showcase',
                'blurb' => 'Portfolio-first design with large app screenshots and CV details.',
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
     * @return array{avatar: ?string, apps: array<int, array{logo: ?string, shots: list<string>}>}
     */
    public static function media(User $creator, Collection $apps): array
    {
        $appMedia = [];

        foreach ($apps as $app) {
            $appMedia[$app->id] = [
                'logo' => PdfImage::fromPublicDisk($app->logo, 240),
                'shots' => collect($app->images ?? [])
                    ->take(3)
                    ->map(fn (string $path) => PdfImage::fromPublicDisk($path, 720))
                    ->filter()
                    ->values()
                    ->all(),
            ];
        }

        return [
            'avatar' => PdfImage::fromUrl($creator->avatar, 320),
            'apps' => $appMedia,
        ];
    }
}
