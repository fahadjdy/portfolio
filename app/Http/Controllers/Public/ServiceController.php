<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('public.services.index', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function show(Service $service)
    {
        abort_unless($service->is_active, 404);

        return view('public.services.show', [
            'service' => $service,
            'faqs' => Faq::active()->for('service', $service->id)->ordered()->get(),
            'services' => Service::active()->ordered()->where('id', '!=', $service->id)->get(),
        ]);
    }
}
