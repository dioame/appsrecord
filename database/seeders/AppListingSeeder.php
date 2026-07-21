<?php

namespace Database\Seeders;

use App\Models\AppListing;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AppListingSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'demo@appsrecord.test'],
            [
                'name' => 'Demo Publisher',
                'password' => 'password',
                'email_verified_at' => now(),
            ]
        );

        Storage::disk('public')->makeDirectory('apps/logos');
        Storage::disk('public')->makeDirectory('apps/screenshots');

        $catalog = [
            'productivity' => [
                [
                    'name' => 'FocusFlow',
                    'platform' => 'mobile',
                    'description' => 'A calm deep-work timer with task batches, break reminders, and weekly focus reports so you stay in flow without burnout.',
                    'colors' => ['#0071E3', '#34C759'],
                ],
                [
                    'name' => 'NoteNest',
                    'platform' => 'web',
                    'description' => 'Capture quick notes, checklists, and voice memos in one place. Sync across devices and pin what matters today.',
                    'colors' => ['#5856D6', '#AF52DE'],
                ],
                [
                    'name' => 'DayStack',
                    'platform' => 'desktop',
                    'description' => 'Plan your day in stacked time blocks. Drag tasks, set priorities, and close the loop every evening.',
                    'colors' => ['#FF9500', '#FF3B30'],
                ],
            ],
            'business' => [
                [
                    'name' => 'InvoiceKit',
                    'platform' => 'web',
                    'description' => 'Create polished invoices in seconds, track payments, and send reminders clients actually open.',
                    'colors' => ['#1D1D1F', '#0071E3'],
                ],
                [
                    'name' => 'LeadPulse',
                    'platform' => 'desktop',
                    'description' => 'Lightweight CRM for freelancers and small teams. Pipeline stages, follow-ups, and deal notes in one board.',
                    'colors' => ['#30B0C7', '#0071E3'],
                ],
            ],
            'education' => [
                [
                    'name' => 'StudyOrbit',
                    'platform' => 'mobile',
                    'description' => 'Spaced-repetition flashcards with streaks and gentle reminders. Built for exams, languages, and lifelong learning.',
                    'colors' => ['#FF2D55', '#5856D6'],
                ],
                [
                    'name' => 'LectureLoop',
                    'platform' => 'web',
                    'description' => 'Record lectures, auto-chapter them, and quiz yourself from the transcript. Perfect for students on the go.',
                    'colors' => ['#34C759', '#30B0C7'],
                ],
            ],
            'health-fitness' => [
                [
                    'name' => 'PulseTrack',
                    'platform' => 'mobile',
                    'description' => 'Log workouts, recovery, and sleep in a clean daily ring. Trends that help you train smarter, not harder.',
                    'colors' => ['#FF3B30', '#FF9500'],
                ],
                [
                    'name' => 'MindQuiet',
                    'platform' => 'mobile',
                    'description' => 'Guided breathing, short meditations, and mood check-ins designed for busy days and restless nights.',
                    'colors' => ['#5856D6', '#64D2FF'],
                ],
            ],
            'finance' => [
                [
                    'name' => 'BudgetBeam',
                    'platform' => 'web',
                    'description' => 'See where your money goes with envelopes, smart categories, and a monthly forecast you can trust.',
                    'colors' => ['#34C759', '#1D1D1F'],
                ],
                [
                    'name' => 'SplitFair',
                    'platform' => 'mobile',
                    'description' => 'Split bills with friends, settle up in one tap, and keep shared trips organized without the awkward math.',
                    'colors' => ['#0071E3', '#FF9500'],
                ],
            ],
            'entertainment' => [
                [
                    'name' => 'PixelQuest',
                    'platform' => 'mobile',
                    'description' => 'A cozy pixel adventure with daily quests, collectible characters, and offline play for short sessions.',
                    'colors' => ['#AF52DE', '#FF2D55'],
                ],
                [
                    'name' => 'ReelRoom',
                    'platform' => 'web',
                    'description' => 'Curate watchlists, rate scenes, and share short reviews with friends who love the same shows.',
                    'colors' => ['#FF3B30', '#1D1D1F'],
                ],
            ],
            'social' => [
                [
                    'name' => 'CircleUp',
                    'platform' => 'mobile',
                    'description' => 'Private circles for close friends. Share updates, plans, and photos without the public feed noise.',
                    'colors' => ['#0071E3', '#AF52DE'],
                ],
                [
                    'name' => 'HangSoon',
                    'platform' => 'web',
                    'description' => 'Propose hangouts, vote on times, and lock plans fast. Calendar-friendly invites for real-life meetups.',
                    'colors' => ['#FF9500', '#FF2D55'],
                ],
            ],
            'utilities' => [
                [
                    'name' => 'ScanShelf',
                    'platform' => 'desktop',
                    'description' => 'Scan documents, receipts, and IDs into tidy folders. OCR search finds anything in seconds.',
                    'colors' => ['#86868B', '#0071E3'],
                ],
                [
                    'name' => 'WidgetBox',
                    'platform' => 'mobile',
                    'description' => 'Home-screen widgets for weather, transit, and quick actions. Customize layouts without clutter.',
                    'colors' => ['#30B0C7', '#5856D6'],
                ],
            ],
        ];

        foreach ($catalog as $categorySlug => $apps) {
            $category = Category::query()->where('slug', $categorySlug)->first();

            if (! $category) {
                continue;
            }

            foreach ($apps as $appData) {
                $slug = Str::slug($appData['name']);

                $existing = AppListing::query()->where('slug', $slug)->first();

                if ($existing) {
                    $existing->update([
                        'platform' => $appData['platform'] ?? 'mobile',
                        'author' => $existing->author ?: ($appData['author'] ?? $user->name),
                    ]);
                    continue;
                }

                $logoPath = $this->makeLogo($slug, $appData['name'], $appData['colors'][0], $appData['colors'][1]);
                $screenshots = [
                    $this->makeScreenshot($slug, 1, $appData['name'], $appData['colors'][0], 'Home'),
                    $this->makeScreenshot($slug, 2, $appData['name'], $appData['colors'][1], 'Details'),
                    $this->makeScreenshot($slug, 3, $appData['name'], $appData['colors'][0], 'Activity'),
                ];

                AppListing::query()->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'platform' => $appData['platform'] ?? 'mobile',
                    'name' => $appData['name'],
                    'author' => $appData['author'] ?? $user->name,
                    'slug' => $slug,
                    'description' => $appData['description'],
                    'logo' => $logoPath,
                    'images' => $screenshots,
                    'is_published' => true,
                ]);
            }
        }
    }

    private function makeLogo(string $slug, string $name, string $from, string $to): string
    {
        $size = 512;
        $image = imagecreatetruecolor($size, $size);
        imagesavealpha($image, true);

        $this->fillGradient($image, $size, $size, $from, $to);

        $initial = strtoupper(Str::substr($name, 0, 1));
        $white = imagecolorallocate($image, 255, 255, 255);
        $font = 5;
        $textWidth = imagefontwidth($font) * strlen($initial);
        $textHeight = imagefontheight($font);
        imagestring(
            $image,
            $font,
            (int) (($size - $textWidth) / 2),
            (int) (($size - $textHeight) / 2),
            $initial,
            $white
        );

        // Draw a larger letter using multiple scaled strings for visibility
        $this->drawCenteredLabel($image, $size, $size, $initial, $white, 8);

        $path = "apps/logos/{$slug}.png";
        $full = Storage::disk('public')->path($path);
        imagepng($image, $full);
        imagedestroy($image);

        return $path;
    }

    private function makeScreenshot(string $slug, int $index, string $name, string $color, string $label): string
    {
        $width = 720;
        $height = 1280;
        $image = imagecreatetruecolor($width, $height);
        $bg = $this->hexToRgb('#F5F5F7');
        $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        $accent = $this->hexToRgb($color);
        $accentColor = imagecolorallocate($image, $accent[0], $accent[1], $accent[2]);
        $dark = imagecolorallocate($image, 29, 29, 31);
        $muted = imagecolorallocate($image, 134, 134, 139);
        $white = imagecolorallocate($image, 255, 255, 255);
        $card = imagecolorallocate($image, 255, 255, 255);

        // Status bar strip
        imagefilledrectangle($image, 0, 0, $width, 72, $accentColor);

        // App header card
        imagefilledrectangle($image, 40, 120, $width - 40, 280, $card);
        imagefilledrectangle($image, 64, 148, 160, 244, $accentColor);
        imagestring($image, 5, 184, 168, $this->safeText($name, 22), $dark);
        imagestring($image, 3, 184, 204, $this->safeText($label.' screen', 28), $muted);

        // Content blocks
        $y = 320;
        for ($i = 0; $i < 5; $i++) {
            imagefilledrectangle($image, 40, $y, $width - 40, $y + 140, $card);
            imagefilledrectangle($image, 64, $y + 28, 120, $y + 84, $accentColor);
            imagestring($image, 4, 148, $y + 36, $this->safeText("Section ".($i + 1), 28), $dark);
            imagestring($image, 2, 148, $y + 68, $this->safeText('Sample content for '.$name, 36), $muted);
            $y += 168;
        }

        // Bottom CTA
        imagefilledrectangle($image, 40, $height - 140, $width - 40, $height - 60, $accentColor);
        imagestring($image, 5, (int) (($width - imagefontwidth(5) * 8) / 2), $height - 112, 'Open App', $white);

        $path = "apps/screenshots/{$slug}-{$index}.png";
        $full = Storage::disk('public')->path($path);
        imagepng($image, $full);
        imagedestroy($image);

        return $path;
    }

    private function fillGradient($image, int $width, int $height, string $fromHex, string $toHex): void
    {
        $from = $this->hexToRgb($fromHex);
        $to = $this->hexToRgb($toHex);

        for ($y = 0; $y < $height; $y++) {
            $ratio = $y / max($height - 1, 1);
            $r = (int) round($from[0] + ($to[0] - $from[0]) * $ratio);
            $g = (int) round($from[1] + ($to[1] - $from[1]) * $ratio);
            $b = (int) round($from[2] + ($to[2] - $from[2]) * $ratio);
            $color = imagecolorallocate($image, $r, $g, $b);
            imageline($image, 0, $y, $width, $y, $color);
        }
    }

    private function drawCenteredLabel($image, int $width, int $height, string $text, int $color, int $scale): void
    {
        $font = 5;
        $charW = imagefontwidth($font);
        $charH = imagefontheight($font);
        $srcW = $charW * strlen($text);
        $srcH = $charH;

        $temp = imagecreatetruecolor($srcW, $srcH);
        imagesavealpha($temp, true);
        $transparent = imagecolorallocatealpha($temp, 0, 0, 0, 127);
        imagefill($temp, 0, 0, $transparent);
        imagestring($temp, $font, 0, 0, $text, $color);

        $destW = $srcW * $scale;
        $destH = $srcH * $scale;
        imagecopyresampled(
            $image,
            $temp,
            (int) (($width - $destW) / 2),
            (int) (($height - $destH) / 2),
            0,
            0,
            $destW,
            $destH,
            $srcW,
            $srcH
        );
        imagedestroy($temp);
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }

    private function safeText(string $text, int $maxLen): string
    {
        $text = preg_replace('/[^\x20-\x7E]/', '', $text) ?? $text;

        return Str::limit($text, $maxLen, '');
    }
}
