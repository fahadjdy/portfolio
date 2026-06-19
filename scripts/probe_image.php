<?php

// One-off probe: confirm GD can encode WebP via Intervention (run with: php artisan tinker scripts/probe_image.php)
$img = \Intervention\Image\Laravel\Facades\Image::create(400, 200);
\Illuminate\Support\Facades\Storage::disk('public')->put('test/probe.webp', (string) $img->toWebp(80));

$ok = \Illuminate\Support\Facades\Storage::disk('public')->exists('test/probe.webp');
echo $ok ? 'WEBP_OK size='.\Illuminate\Support\Facades\Storage::disk('public')->size('test/probe.webp') : 'WEBP_FAIL';
echo PHP_EOL;

\Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('test');
