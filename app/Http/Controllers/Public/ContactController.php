<?php

namespace App\Http\Controllers\Public;

use App\Actions\Leads\CreateLead;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\PublicLeadRequest;
use App\Models\Service;

class ContactController extends Controller
{
    public function show()
    {
        return view('public.contact', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function store(PublicLeadRequest $request, CreateLead $createLead)
    {
        $createLead->handle($request->leadData(), $request);

        return back()->with('success', "Thanks for reaching out! I'll get back to you within 1–2 business days.");
    }
}
