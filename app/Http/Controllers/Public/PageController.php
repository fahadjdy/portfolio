<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\PageSection;
use App\Models\SkillCategory;
use App\Services\SchemaBuilder;

class PageController extends Controller
{
    public function about(SchemaBuilder $schema)
    {
        return view('public.about', [
            'about' => PageSection::forPage('about')->where('section_key', 'about_main')->first(),
            'skillCategories' => SkillCategory::active()->ordered()
                ->with(['skills' => fn ($q) => $q->active()->ordered()])->get(),
            'experiences' => Experience::active()->ordered()->get(),
            'education' => Education::active()->ordered()->get(),
            'schema' => $schema->graph([
                $schema->person(),
                $schema->website(),
                $schema->breadcrumb([['Home', route('home')], ['About', route('about')]]),
            ]),
        ]);
    }
}
