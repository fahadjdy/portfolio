<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

/**
 * GD-based image optimization (shared-hosting safe).
 *
 * Every uploaded image is auto-oriented, capped to a sane max dimension,
 * converted to WebP, and (for galleries) emitted at multiple widths with a
 * srcset map + a JPEG fallback. Files land on the public disk under
 * {dir}/{Y}/{m}/{uuid}-{w}.webp and are served via storage:link.
 */
class ImageService
{
    public const MAX_EDGE = 2400;

    /**
     * Store a single optimized WebP (logos, covers, avatars, og images, etc.).
     * Returns the relative public-disk path.
     */
    public function storeOptimized(UploadedFile $file, string $dir = 'images', int $maxWidth = self::MAX_EDGE, int $quality = 80): string
    {
        $image = Image::read($file->getRealPath());
        $image->scaleDown(width: $maxWidth, height: $maxWidth);

        $path = $this->path($dir, $image->width());
        Storage::disk('public')->put($path, (string) $image->toWebp($quality));

        return $path;
    }

    /**
     * Store a responsive set (project galleries): multiple WebP widths + a
     * JPEG fallback at the largest size. Returns the column payload.
     *
     * @return array{path:string, path_webp:string, path_md:?string, path_thumb:?string, srcset:array<int,string>, width:int, height:int}
     */
    public function storeResponsive(UploadedFile $file, string $dir = 'projects', array $widths = [400, 800, 1280, 1920], int $quality = 80): array
    {
        $source = Image::read($file->getRealPath());
        $source->scaleDown(width: self::MAX_EDGE, height: self::MAX_EDGE);

        $maxWidth = $source->width();
        $maxHeight = $source->height();
        $base = $this->basePath($dir);

        $srcset = [];
        $byWidth = [];
        foreach ($widths as $w) {
            if ($w > $maxWidth && $w !== $widths[0]) {
                continue; // never upscale (but always keep the smallest)
            }
            $clone = clone $source;
            $clone->scaleDown(width: $w);
            $path = "{$base}-{$clone->width()}.webp";
            Storage::disk('public')->put($path, (string) $clone->toWebp($quality));
            $srcset[$clone->width()] = $path;
            $byWidth[$w] = $path;
        }

        // JPEG fallback at the largest produced width.
        $fallbackPath = "{$base}.jpg";
        Storage::disk('public')->put($fallbackPath, (string) $source->toJpeg($quality));

        ksort($srcset);
        $largestWebp = end($srcset) ?: $fallbackPath;

        return [
            'path' => $fallbackPath,
            'path_webp' => $largestWebp,
            'path_md' => $byWidth[800] ?? $byWidth[$widths[0]] ?? $largestWebp,
            'path_thumb' => $byWidth[400] ?? $byWidth[$widths[0]] ?? $largestWebp,
            'srcset' => $srcset,
            'width' => $maxWidth,
            'height' => $maxHeight,
        ];
    }

    /** Delete a file and any sibling derivatives sharing its base name. */
    public function delete(?string ...$paths): void
    {
        foreach (array_filter($paths) as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function path(string $dir, int $width): string
    {
        return $this->basePath($dir)."-{$width}.webp";
    }

    private function basePath(string $dir): string
    {
        return trim($dir, '/').'/'.now()->format('Y/m').'/'.Str::uuid()->toString();
    }
}
