<?php

use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\ExperienceController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\ProjectImageController;
use App\Http\Controllers\Admin\ResumeController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillCategoryController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\TechTagController;
use App\Http\Controllers\Admin\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Keep the name 'dashboard' (login redirects here).
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('admin.')->group(function () {
        // Leads (lead generation)
        Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::patch('leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
        Route::post('leads/{lead}/notes', [LeadController::class, 'storeNote'])->name('leads.notes.store');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // Reorder endpoints for sortable resources
        foreach ([
            'skill-categories' => SkillCategoryController::class,
            'skills' => SkillController::class,
            'experiences' => ExperienceController::class,
            'education' => EducationController::class,
            'tech-tags' => TechTagController::class,
            'services' => ServiceController::class,
            'testimonials' => TestimonialController::class,
            'social-links' => SocialLinkController::class,
            'blog-categories' => BlogCategoryController::class,
            'projects' => ProjectController::class,
        ] as $uri => $controller) {
            Route::post("{$uri}/reorder", [$controller, 'reorder'])->name(str_replace('-', '_', $uri).'.reorder');
        }

        // Standard CRUD resources
        Route::resource('skill-categories', SkillCategoryController::class)->except('show');
        Route::resource('skills', SkillController::class)->except('show');
        Route::resource('experiences', ExperienceController::class)->except('show');
        Route::resource('education', EducationController::class)->except('show')->parameters(['education' => 'education']);
        Route::resource('tech-tags', TechTagController::class)->except('show');
        Route::resource('services', ServiceController::class)->except('show');
        Route::resource('testimonials', TestimonialController::class)->except('show');
        Route::resource('social-links', SocialLinkController::class)->except('show');
        Route::resource('blog-categories', BlogCategoryController::class)->except('show');
        Route::resource('blog-posts', BlogPostController::class)->except('show');
        Route::resource('resumes', ResumeController::class)->only(['index', 'store', 'destroy']);

        // Projects (with nested panels/features synced inline + separate gallery)
        Route::resource('projects', ProjectController::class)->except('show');
        Route::post('projects/{project}/images', [ProjectImageController::class, 'store'])->name('projects.images.store');
        Route::delete('project-images/{image}', [ProjectImageController::class, 'destroy'])->name('project_images.destroy');

        // Pages (Hero / About singletons)
        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::put('pages/{section}', [PageController::class, 'update'])->name('pages.update');

        // Settings (General / SEO / Contact)
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        // Maintenance tools (login-only deploy helpers)
        Route::get('tools', [MaintenanceController::class, 'index'])->name('tools.index');
        Route::get('tools/{action}', [MaintenanceController::class, 'run'])
            ->where('action', 'migrate|seed|storage|clear|cache')->name('tools.run');
    });
});
