<?php

namespace App\Http\Controllers\Public;

use App\Actions\Leads\CreateLead;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\PublicLeadRequest;
use App\Models\Service;
use App\Services\SchemaBuilder;

class ContactController extends Controller
{
    public function show(SchemaBuilder $schema)
    {
        return view('public.contact', [
            'services' => Service::active()->ordered()->get(),
            'schema' => $schema->graph([
                $schema->person(),
                $schema->website(),
                $schema->webPage(route('contact'), 'Contact', type: 'ContactPage'),
                $schema->breadcrumb([['Home', route('home')], ['Contact', route('contact')]]),
            ]),
        ]);
    }

    public function store(PublicLeadRequest $request, CreateLead $createLead)
    {
        $createLead->handle($request->leadData(), $request);

        return back()->with('success', "Thanks for reaching out! I'll get back to you within 1–2 business days.");
    }
}
