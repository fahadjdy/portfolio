<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Inertia\Inertia;

/**
 * Login-only maintenance tools. Unlike the token-less /deploy/* routes (used by
 * CI), these live under /admin and require an authenticated admin (auth + admin
 * middleware on the route group). Forward-safe Artisan commands only.
 */
class MaintenanceController extends Controller
{
    private const ACTIONS = [
        'migrate' => 'Run database migrations (--force)',
        'seed' => 'Seed the database (--force)',
        'storage' => 'Create the storage symlink',
        'clear' => 'Clear config/route/view/cache',
        'cache' => 'Cache config + routes + views (production speed)',
    ];

    public function index()
    {
        return Inertia::render('admin/Tools', [
            'actions' => collect(self::ACTIONS)->map(fn ($label, $key) => [
                'key' => $key,
                'label' => $label,
                'url' => route('admin.tools.run', $key),
            ])->values(),
        ]);
    }

    public function run(string $action)
    {
        @set_time_limit(300);

        try {
            match ($action) {
                'migrate' => Artisan::call('migrate', ['--force' => true]),
                'seed' => Artisan::call('db:seed', ['--force' => true]),
                'storage' => Artisan::call('storage:link'),
                'clear' => Artisan::call('optimize:clear'),
                'cache' => Artisan::call('optimize'),
            };

            $output = trim(Artisan::output()) ?: '(no output)';

            return response("✓ {$action} completed:\n\n{$output}", 200)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        } catch (\Throwable $e) {
            return response("✗ {$action} failed:\n\n".$e->getMessage(), 500)
                ->header('Content-Type', 'text/plain; charset=utf-8');
        }
    }
}
