<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\Resume;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    /**
     * Public "Download CV". If the admin uploaded a current resume file, serve
     * that; otherwise generate a clean, structured PDF from the live content.
     */
    public function download()
    {
        $resume = Resume::current()->latest()->first();

        if ($resume && Storage::disk('public')->exists($resume->file_path)) {
            $resume->increment('downloads');

            return Storage::disk('public')->download(
                $resume->file_path,
                $resume->original_name ?: 'Fahad-Jadiya-Resume.pdf',
            );
        }

        return $this->generate();
    }

    private function generate()
    {
        $data = [
            'name' => settings('site_name', 'Fahad Jadiya'),
            'tagline' => settings('tagline', 'Senior Full-Stack Developer'),
            'email' => settings('contact_email'),
            'phone' => settings('contact_phone'),
            'location' => settings('contact_address'),
            'website' => config('app.url'),
            'summary' => optional(PageSection::forPage('about')->where('section_key', 'about_main')->first())->body
                ?? optional(PageSection::forPage('home')->where('section_key', 'about_intro')->first())->body,
            'socials' => SocialLink::active()->ordered()->get(),
            'skillCategories' => SkillCategory::active()->ordered()->with(['skills' => fn ($q) => $q->active()->ordered()])->get(),
            'experiences' => Experience::active()->ordered()->get(),
            'education' => Education::active()->ordered()->get(),
            'projects' => Project::published()->featured()->ordered()->with('techTags')->get(),
        ];

        $pdf = Pdf::loadView('pdf.resume', $data)->setPaper('a4');

        return $pdf->download('Fahad-Jadiya-Resume.pdf');
    }
}
