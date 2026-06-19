<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ResumeController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/Resumes', [
            'resumes' => Resume::latest()->get()->map(fn (Resume $r) => [
                'id' => $r->id,
                'label' => $r->label,
                'original_name' => $r->original_name,
                'size' => $r->size,
                'is_current' => $r->is_current,
                'downloads' => $r->downloads,
                'url' => $r->url,
                'created_at' => $r->created_at,
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['nullable', 'string', 'max:120'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('resumes', 'public');

        Resume::query()->update(['is_current' => false]);

        Resume::create([
            'label' => $data['label'] ?? 'Resume',
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'is_current' => true,
        ]);

        return back()->with('success', 'Resume uploaded and set as current.');
    }

    public function destroy(Resume $resume)
    {
        Storage::disk('public')->delete($resume->file_path);
        $resume->delete();

        return back()->with('success', 'Resume deleted.');
    }
}
