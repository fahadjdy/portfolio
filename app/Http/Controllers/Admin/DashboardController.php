<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Resume;
use App\Models\Service;
use App\Models\Skill;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Dashboard', [
            'stats' => [
                'leadsTotal' => Lead::count(),
                'leadsNew' => Lead::where('status', 'new')->count(),
                'projects' => Project::count(),
                'projectsPublished' => Project::published()->count(),
                'services' => Service::count(),
                'skills' => Skill::count(),
                'testimonials' => Testimonial::count(),
                'cvDownloads' => (int) Resume::sum('downloads'),
            ],
            'leadsByStatus' => Lead::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')->pluck('count', 'status'),
            'recentLeads' => Lead::latest()->take(8)->get(['id', 'name', 'email', 'status', 'created_at']),
        ]);
    }
}
