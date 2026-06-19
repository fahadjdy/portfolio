<?php

use App\Http\Controllers\Public\BlogController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\PageController;
use App\Http\Controllers\Public\ProjectController;
use App\Http\Controllers\Public\ResumeController;
use App\Http\Controllers\Public\SeoController;
use App\Http\Controllers\Public\ServiceController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public site (server-rendered Blade — SEO/AEO/GEO first)
|--------------------------------------------------------------------------
| The marketing/portfolio pages are plain Blade for the fastest possible
| load and best crawlability. The admin panel (Inertia + Vue) lives under
| /admin and is wired in Phase 2.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{service:slug}', [ServiceController::class, 'show'])->name('services.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:8,1')->name('contact.store');
Route::get('/resume/download', [ResumeController::class, 'download'])->name('resume.download');

// SEO / AEO / GEO — dynamic, DB-driven
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');
Route::get('/llms.txt', [SeoController::class, 'llms'])->name('llms');

/*
|--------------------------------------------------------------------------
| Deployment helpers (shared hosting, no CLI access)
|--------------------------------------------------------------------------
| Token-less GET URLs so safe Artisan tasks can be run from the browser.
| Only forward-safe commands are allowed (no fresh/rollback/down — no data
| loss). Disable after setup with DEPLOY_TOOLS=false in .env.
*/
if (config('platform.deploy_tools')) {
    Route::get('/deploy', function () {
        $lines = [
            'Fahad Jadiya Portfolio — deploy tools (token-less).',
            'Disable after setup with DEPLOY_TOOLS=false in .env',
            '',
            '  /deploy/init     — create storage/cache folders (run this FIRST)',
            '  /deploy/migrate  — run database migrations (--force)',
            '  /deploy/seed     — seed the database (--force)',
            '  /deploy/clear    — clear config/route/view/cache',
            '  /deploy/cache    — cache config+routes+views (production speed)',
            '  /deploy/link     — create the storage symlink',
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain; charset=utf-8');
    })->name('deploy.index');

    Route::get('/deploy/{action}', function (string $action) {
        @set_time_limit(300);
        @ignore_user_abort(true);

        try {
            if ($action === 'init') {
                $dirs = [
                    storage_path('framework/cache/data'),
                    storage_path('framework/sessions'),
                    storage_path('framework/views'),
                    storage_path('framework/testing'),
                    storage_path('app/public'),
                    storage_path('logs'),
                    base_path('bootstrap/cache'),
                ];
                $created = [];
                foreach ($dirs as $dir) {
                    if (! is_dir($dir)) {
                        @mkdir($dir, 0775, true);
                        $created[] = str_replace(base_path().DIRECTORY_SEPARATOR, '', $dir);
                    }
                }
                Artisan::call('optimize:clear');

                return response(
                    "✓ deploy/init — storage prepared\n\nCreated:\n  ".
                    implode("\n  ", $created ?: ['(all folders already existed)']).
                    "\n\n".trim(Artisan::output()).
                    "\n\nNext: /deploy/migrate  →  /deploy/seed  →  /deploy/link",
                    200
                )->header('Content-Type', 'text/plain; charset=utf-8');
            }

            match ($action) {
                'migrate' => Artisan::call('migrate', ['--force' => true]),
                'seed' => Artisan::call('db:seed', ['--force' => true]),
                'clear' => Artisan::call('optimize:clear'),
                'cache' => Artisan::call('optimize'),
                'link' => Artisan::call('storage:link'),
            };

            $output = trim(Artisan::output());

            return response("✓ deploy/{$action} completed:\n\n".$output, 200)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        } catch (Throwable $e) {
            return response("✗ deploy/{$action} failed:\n\n".$e->getMessage(), 500)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        }
    })->where('action', 'init|migrate|seed|clear|cache|link')->name('deploy.run');
}

require __DIR__.'/admin.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
