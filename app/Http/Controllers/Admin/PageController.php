<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function __construct(private ImageService $images) {}

    public function index()
    {
        $sections = PageSection::orderBy('page')->orderBy('position')->get()
            ->map(fn (PageSection $s) => [
                'id' => $s->id,
                'page' => $s->page,
                'section_key' => $s->section_key,
                'heading' => $s->heading,
                'subheading' => $s->subheading,
                'body' => $s->body,
                'image' => $s->image_url,
                'data' => $s->data,
            ]);

        return Inertia::render('admin/Pages', ['sections' => $sections]);
    }

    public function update(Request $request, PageSection $section)
    {
        $data = $request->validate([
            'heading' => ['nullable', 'string', 'max:200'],
            'subheading' => ['nullable', 'string', 'max:200'],
            'body' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'data' => ['nullable', 'array'],
        ]);

        $payload = [
            'heading' => $data['heading'] ?? null,
            'subheading' => $data['subheading'] ?? null,
            'body' => $data['body'] ?? null,
            'data' => $data['data'] ?? $section->data,
        ];

        if ($request->hasFile('image')) {
            $this->images->delete($section->image && ! str_starts_with($section->image, 'http') ? $section->image : null);
            $payload['image'] = $this->images->storeOptimized($request->file('image'), 'pages', 1600);
        }

        $section->update($payload);

        return back()->with('success', 'Section saved.');
    }
}
