<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\TechTag;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $tech = $request->query('tech');

        $projects = Project::published()->ordered()->with('techTags')
            ->when($tech, fn ($q) => $q->whereHas('techTags', fn ($t) => $t->where('slug', $tech)))
            ->get();

        return view('public.projects.index', [
            'projects' => $projects,
            'techTags' => TechTag::query()->whereHas('projects', fn ($q) => $q->published())
                ->orderBy('name')->get(),
            'activeTech' => $tech,
        ]);
    }

    public function show(Project $project)
    {
        abort_unless($project->status->value === 'published', 404);

        $project->load(['panels.features', 'images', 'techTags', 'testimonials' => fn ($q) => $q->active()]);

        $related = Project::published()
            ->where('id', '!=', $project->id)
            ->ordered()
            ->with('techTags')
            ->limit(3)
            ->get();

        return view('public.projects.show', [
            'project' => $project,
            'related' => $related,
        ]);
    }
}
