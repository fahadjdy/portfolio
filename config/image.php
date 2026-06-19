<?php

use Intervention\Image\Drivers\Gd\Driver;

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | GD is used (not Imagick) because most shared/cPanel hosts ship GD but not
    | Imagick. PHP 8.2's bundled GD supports WebP, which is all we need.
    |
    */

    'driver' => Driver::class,

    'options' => [
        'autoOrientation' => true,
        'decodeAnimation' => true,
        'blendingColor' => 'ffffff',
    ],

];
