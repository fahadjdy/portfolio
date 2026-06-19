<?php

use App\Services\SettingsService;
use Illuminate\Support\Facades\Storage;

if (! function_exists('settings')) {
    /**
     * Access the cached settings store.
     *  settings()            -> the SettingsService instance
     *  settings('site_name') -> a single value (with optional default)
     */
    function settings(?string $key = null, mixed $default = null): mixed
    {
        $service = app(SettingsService::class);

        return $key === null ? $service : $service->get($key, $default);
    }
}

if (! function_exists('img_url')) {
    /**
     * Resolve a stored image path (or absolute URL) to a public URL.
     * Falls back to $default when the path is empty.
     */
    function img_url(?string $path, ?string $default = null): ?string
    {
        if (blank($path)) {
            return $default;
        }

        return str_starts_with($path, 'http') ? $path : Storage::disk('public')->url($path);
    }
}

if (! function_exists('setting_image')) {
    /** Resolve an image-type setting to a public URL. */
    function setting_image(string $key, ?string $default = null): ?string
    {
        return img_url(settings($key), $default);
    }
}
