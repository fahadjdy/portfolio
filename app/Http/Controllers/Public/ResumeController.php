<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Resume;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function download()
    {
        $resume = Resume::current()->latest()->first();

        abort_if(! $resume || ! Storage::disk('public')->exists($resume->file_path), 404, 'Resume not available.');

        $resume->increment('downloads');

        return Storage::disk('public')->download(
            $resume->file_path,
            $resume->original_name ?: 'Fahad-Jadiya-Resume.pdf',
        );
    }
}
