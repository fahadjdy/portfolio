<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\Project;
use App\Models\Service;
use App\Models\SkillCategory;
use App\Models\TechTag;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        return view('public.home', [
            'hero' => PageSection::forPage('home')->where('section_key', 'hero')->first(),
            'aboutIntro' => PageSection::forPage('home')->where('section_key', 'about_intro')->first(),
            'skillCategories' => SkillCategory::active()->ordered()
                ->with(['skills' => fn ($q) => $q->active()->ordered()])->get(),
            'featuredTech' => TechTag::active()->featured()->ordered()->get(),
            'experiences' => Experience::active()->ordered()->get(),
            'services' => Service::active()->ordered()->get(),
            'featuredProjects' => Project::published()->featured()->ordered()
                ->with('techTags')->get(),
            'testimonials' => Testimonial::active()->featured()->ordered()->get(),
            'faqs' => Faq::active()->global()->ordered()->get(),
        ]);
    }
}
