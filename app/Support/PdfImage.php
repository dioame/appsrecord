<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PdfImage
{
    /**
     * Embed a public-disk image as a DomPDF-safe JPEG data URI.
     */
    public static function fromPublicDisk(?string $path, int $maxWidth = 900): ?string
    {
        if (! filled($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $absolute = Storage::disk('public')->path($path);
        $contents = @file_get_contents($absolute);

        if ($contents === false || $contents === '') {
            $contents = Storage::disk('public')->get($path);
        }

        return self::normalizeToJpegDataUri($contents, $maxWidth);
    }

    /**
     * Embed a remote image (e.g. Google avatar) as a DomPDF-safe JPEG data URI.
     */
    public static function fromUrl(?string $url, int $maxWidth = 400): ?string
    {
        if (! filled($url)) {
            return null;
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders(['Accept' => 'image/*'])
                ->get($url);

            if (! $response->successful() || $response->body() === '') {
                return null;
            }

            return self::normalizeToJpegDataUri($response->body(), $maxWidth);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * DomPDF reliably renders JPEG/PNG; convert WebP/GIF/etc. via GD when available.
     */
    public static function normalizeToJpegDataUri(string $contents, int $maxWidth = 900): ?string
    {
        if ($contents === '') {
            return null;
        }

        if (function_exists('imagecreatefromstring')) {
            $image = @imagecreatefromstring($contents);

            if ($image !== false) {
                $width = imagesx($image);
                $height = imagesy($image);

                if ($width > $maxWidth && $width > 0) {
                    $newHeight = (int) max(1, round($height * ($maxWidth / $width)));
                    $resized = imagecreatetruecolor($maxWidth, $newHeight);
                    $white = imagecolorallocate($resized, 255, 255, 255);
                    imagefilledrectangle($resized, 0, 0, $maxWidth, $newHeight, $white);
                    imagecopyresampled($resized, $image, 0, 0, 0, 0, $maxWidth, $newHeight, $width, $height);
                    imagedestroy($image);
                    $image = $resized;
                }

                ob_start();
                imagejpeg($image, null, 82);
                $contents = (string) ob_get_clean();
                imagedestroy($image);

                if ($contents !== '') {
                    return 'data:image/jpeg;base64,'.base64_encode($contents);
                }
            }
        }

        $mime = self::detectMime($contents) ?? 'image/jpeg';

        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/gif'], true)) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }

    protected static function detectMime(string $contents): ?string
    {
        if (str_starts_with($contents, "\xFF\xD8\xFF")) {
            return 'image/jpeg';
        }

        if (str_starts_with($contents, "\x89PNG\r\n\x1A\n")) {
            return 'image/png';
        }

        if (str_starts_with($contents, 'GIF87a') || str_starts_with($contents, 'GIF89a')) {
            return 'image/gif';
        }

        return null;
    }
}
