<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Browser deploy tools
    |--------------------------------------------------------------------------
    |
    | Enables the token-less GET /deploy/{action} helper routes so safe Artisan
    | tasks (migrate, seed, clear, cache, link) can be run from the browser on
    | shared hosting without SSH/CLI access. Disable after setup by setting
    | DEPLOY_TOOLS=false in .env.
    |
    */

    'deploy_tools' => env('DEPLOY_TOOLS', false),

    /*
    |--------------------------------------------------------------------------
    | Lead notifications
    |--------------------------------------------------------------------------
    |
    | Where new project-inquiry / contact leads are emailed.
    |
    */

    'lead_notify_email' => env('LEAD_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS')),

];
