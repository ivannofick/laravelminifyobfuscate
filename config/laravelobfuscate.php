<?php
/*
 * This file is part of Laravel Obfuscate.
 *
 * (c) ivannofick <ivannofick@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Conditional Obfuscate
    |--------------------------------------------------------------------------
    |
    | Setting to enable/disable conditional obfuscation in the Laravel application. 
    | If the environment variable 'LARAVEL_OBFUSCATE_CONDITIONAL' is not set, it defaults to 'false'.
    | Obfuscation is a technique used to make the code more difficult to understand by altering its structure while preserving its functionality.
    | When set to 'false', the obfuscation process will not be applied regardless of other settings.
    |
    | Default: false
    |
    */
    'laravel_obfuscate_conditional' => env('LARAVEL_OBFUSCATE_CONDITIONAL', false),


    /*
    |--------------------------------------------------------------------------
    | Ignore Routes
    |--------------------------------------------------------------------------
    |
    | Array of route patterns to be ignored or excluded from the obfuscation process.
    | Developers can add specific route patterns here to prevent them from being obfuscated.
    | For now, the array is empty, meaning all routes are subject to obfuscation unless excluded using patterns.
    |
    */
    'ignore_route' => [
        //   "*/user/*",
        //   "user/*",
        //   "*/user"
    ],

    'laravel_obfuscate_secure_key' => env('LARAVEL_OBFUSCATE_SECURE_KEY', 'ruangapp.com')
];
