<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PdfImage
{
    public static function fromPublicDisk(?string $path): ?string
    {
        if (! filled($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $contents = Storage::disk('public')->get($path);
        $mime = Storage::disk('public')->mimeType($path) ?: 'image/png';

        return self::toDataUri($contents, $mime);
    }

    public static function fromUrl(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        try {
            $response = Http::timeout(8)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $mime = $response->header('Content-Type') ?: 'image/jpeg';
            $mime = explode(';', $mime)[0];

            return self::toDataUri($response->body(), $mime);
        } catch (\Throwable) {
            return null;
        }
    }

    protected static function toDataUri(string $contents, string $mime): ?string
    {
        if ($contents === '') {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}
