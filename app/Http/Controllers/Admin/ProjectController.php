<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\TechTag;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index()
    {
        return Inertia::render('admin/projects/Index', [
            'projects' => Project::ordered()->withCount('panels')->with('techTags:id,name')->get()
                ->map(fn (Project $p) => [
                    'id' => $p->id,
                    'title' => $p->title,
                    'category' => $p->category,
                    'status' => $p->status->value,
                    'is_featured' => $p->is_featured,
                    'panels_count' => $p->panels_count,
                    'tech' => $p->techTags->pluck('name'),
                ]),
        ]);
    }

    public function create()
    {
        return Inertia::render('admin/projects/Form', [
            'project' => null,
            'techTags' => $this->techOptions(),
            'statuses' => ProjectStatus::options(),
        ]);
    }

    public function store(ProjectRequest $request)
    {
        $project = new Project;
        $this->save($project, $request);

        return redirect()->route('admin.projects.index')->with('success', 'Project created.');
    }

    public function edit(Project $project)
    {
        $project->load(['panels.features', 'images', 'techTags:id']);

        return Inertia::render('admin/projects/Form', [
            'project' => [
                ...$project->toArray(),
                'tech_tag_ids' => $project->techTags->pluck('id'),
                'panels' => $project->panels->map(fn ($panel) => [
                    'name' => $panel->name,
                    'icon' => $panel->icon,
                    'description' => $panel->description,
                    'features' => $panel->features->map(fn ($f) => ['title' => $f->title, 'description' => $f->description])->values(),
                ])->values(),
                'images' => $project->images->map(fn ($img) => [
                    'id' => $img->id,
                    'url' => $img->url,
                    'alt' => $img->alt,
                ])->values(),
            ],
            'techTags' => $this->techOptions(),
            'statuses' => ProjectStatus::options(),
        ]);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $this->save($project, $request);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return back()->with('success', 'Project deleted.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->input('ids', []) as $i => $id) {
            Project::where('id', $id)->update(['position' => $i + 1]);
        }

        return back();
    }

    private function save(Project $project, ProjectRequest $request): void
    {
        $data = $request->validated();

        $project->fill([
            'title' => $data['title'],
            'client_name' => $data['client_name'] ?? null,
            'category' => $data['category'] ?? null,
            'summary' => $data['summary'],
            'problem' => $data['problem'] ?? null,
            'solution' => $data['solution'] ?? null,
            'outcome' => $data['outcome'] ?? null,
            'highlights' => array_values(array_filter($data['highlights'] ?? [], fn ($h) => filled($h))),
            'live_url' => $data['live_url'] ?? null,
            'repo_url' => $data['repo_url'] ?? null,
            'year' => $data['year'] ?? null,
            'role' => $data['role'] ?? null,
            'duration' => $data['duration'] ?? null,
            'status' => $data['status'],
            'is_featured' => $request->boolean('is_featured'),
            'seo_title' => $data['seo_title'] ?? null,
            'seo_description' => $data['seo_description'] ?? null,
        ]);

        if (filled($data['slug'] ?? null)) {
            $project->slug = $data['slug'];
        }

        foreach (['cover_image', 'thumbnail', 'og_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($project->{$field} && ! str_starts_with((string) $project->{$field}, 'http')) {
                    $this->images->delete($project->{$field});
                }
                $project->{$field} = $this->images->storeOptimized($request->file($field), 'projects', 1920);
            }
        }

        $project->save();

        $sync = [];
        foreach ($data['tech_tag_ids'] ?? [] as $i => $id) {
            $sync[$id] = ['position' => $i + 1];
        }
        $project->techTags()->sync($sync);

        $project->panels()->delete();
        foreach ($data['panels'] ?? [] as $pi => $panel) {
            $panelModel = $project->panels()->create([
                'name' => $panel['name'],
                'icon' => $panel['icon'] ?? null,
                'description' => $panel['description'] ?? null,
                'position' => $pi + 1,
            ]);
            foreach ($panel['features'] ?? [] as $fi => $feature) {
                $panelModel->features()->create([
                    'project_id' => $project->id,
                    'title' => $feature['title'],
                    'description' => $feature['description'] ?? null,
                    'position' => $fi + 1,
                ]);
            }
        }
    }

    private function techOptions()
    {
        return TechTag::orderBy('name')->get(['id', 'name'])->map(fn ($t) => ['value' => $t->id, 'label' => $t->name]);
    }
}
