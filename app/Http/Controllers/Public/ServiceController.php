<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;
use App\Services\SchemaBuilder;

class ServiceController extends Controller
{
    public function index(SchemaBuilder $schema)
    {
        return view('public.services.index', [
            'services' => Service::active()->ordered()->get(),
            'schema' => $schema->graph([
                $schema->person(),
                $schema->website(),
                $schema->breadcrumb([['Home', route('home')], ['Services', route('services.index')]]),
            ]),
        ]);
    }

    public function show(Service $service, SchemaBuilder $schema)
    {
        abort_unless($service->is_active, 404);

        $faqs = Faq::active()->for('service', $service->id)->ordered()->get();

        return view('public.services.show', [
            'service' => $service,
            'faqs' => $faqs,
            'services' => Service::active()->ordered()->where('id', '!=', $service->id)->get(),
            'schema' => $schema->graph([
                $schema->person(),
                $schema->breadcrumb([
                    ['Home', route('home')],
                    ['Services', route('services.index')],
                    [$service->title, route('services.show', $service)],
                ]),
                $schema->service($service),
                $schema->faqPage($faqs),
            ]),
        ]);
    }
}
