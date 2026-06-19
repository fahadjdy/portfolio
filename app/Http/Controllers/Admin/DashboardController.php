<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\PageVisit;
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
        $monthStart = now()->startOfMonth();

        $trendRaw = PageVisit::where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('count(*) as c'))
            ->groupBy('d')->pluck('c', 'd');

        $trend = collect(range(13, 0))->map(function ($i) use ($trendRaw) {
            $date = now()->subDays($i);

            return [
                'label' => $date->format('d M'),
                'count' => (int) ($trendRaw[$date->format('Y-m-d')] ?? 0),
            ];
        })->values();

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
            'analytics' => [
                'visitsToday' => PageVisit::whereDate('created_at', today())->count(),
                'visitsMonth' => PageVisit::where('created_at', '>=', $monthStart)->count(),
                'visitsTotal' => PageVisit::count(),
                'uniqueMonth' => PageVisit::where('created_at', '>=', $monthStart)
                    ->distinct('ip_address')->count('ip_address'),
                'trend' => $trend,
                'topCountries' => PageVisit::whereNotNull('country')
                    ->select('country', 'country_code', DB::raw('count(*) as count'))
                    ->groupBy('country', 'country_code')->orderByDesc('count')->limit(8)->get(),
                'topPages' => PageVisit::select('path', DB::raw('count(*) as count'))
                    ->groupBy('path')->orderByDesc('count')->limit(8)->get(),
                'devices' => PageVisit::whereNotNull('device')
                    ->select('device', DB::raw('count(*) as count'))
                    ->groupBy('device')->pluck('count', 'device'),
            ],
        ]);
    }
}
