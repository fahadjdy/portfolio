<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function store(Request $request, Project $project)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'alt' => ['nullable', 'string', 'max:200'],
        ]);

        foreach ($request->file('images') as $file) {
            $payload = $this->images->storeResponsive($file, 'projects/gallery');
            $project->images()->create([
                'disk' => 'public',
                'path' => $payload['path'],
                'path_webp' => $payload['path_webp'],
                'path_md' => $payload['path_md'],
                'path_thumb' => $payload['path_thumb'],
                'srcset' => $payload['srcset'],
                'width' => $payload['width'],
                'height' => $payload['height'],
                'alt' => $request->input('alt'),
                'type' => 'screenshot',
            ]);
        }

        return back()->with('success', 'Images uploaded.');
    }

    public function destroy(ProjectImage $image)
    {
        $this->images->delete(
            $image->path,
            $image->path_webp,
            $image->path_md,
            $image->path_thumb,
            ...array_values($image->srcset ?? []),
        );
        $image->delete();

        return back()->with('success', 'Image deleted.');
    }
}
