<?php

namespace App\Providers;

use App\Models\SocialLink;
use App\Services\SettingsService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingsService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Emit absolute HTTPS URLs in production so canonical tags, Open Graph URLs,
        // the sitemap and JSON-LD all match APP_URL — even behind an SSL-terminating proxy.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Share active social links with the public layout partials.
        View::composer(['layouts.partials.header', 'layouts.partials.footer'], function ($view) {
            $view->with('socials', SocialLink::active()->ordered()->get());
        });
    }
}
