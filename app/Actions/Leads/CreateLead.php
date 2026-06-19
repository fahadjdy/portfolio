<?php

namespace App\Actions\Leads;

use App\Mail\NewLeadNotification;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CreateLead
{
    /**
     * Persist a public inquiry and notify the admin by email.
     * A mail failure must never break the public submit, so it's caught + logged.
     */
    public function handle(array $data, Request $request): Lead
    {
        $lead = Lead::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'budget' => $data['budget'] ?? null,
            'project_type' => $data['project_type'] ?? null,
            'service_id' => $data['service_id'] ?? null,
            'source' => $data['source'] ?? 'contact_form',
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
            'referrer' => $request->headers->get('referer'),
        ]);

        $notify = config('platform.lead_notify_email');

        if ($notify) {
            try {
                Mail::to($notify)->send(new NewLeadNotification($lead));
            } catch (\Throwable $e) {
                Log::error('Lead notification email failed: '.$e->getMessage(), ['lead_id' => $lead->id]);
            }
        }

        return $lead;
    }
}
