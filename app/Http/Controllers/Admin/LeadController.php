<?php

namespace App\Http\Controllers\Admin;

use App\Enums\LeadStatus;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $leads = Lead::query()
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->search, fn ($q, $s) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%")
                ->orWhere('company', 'like', "%{$s}%")))
            ->with('service:id,title')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('admin/leads/Index', [
            'leads' => $leads,
            'filters' => $request->only('status', 'search'),
            'statuses' => LeadStatus::options(),
            'counts' => [
                'all' => Lead::count(),
                'new' => Lead::where('status', 'new')->count(),
            ],
        ]);
    }

    public function show(Lead $lead)
    {
        $lead->load(['notes.user:id,name', 'service:id,title']);

        return Inertia::render('admin/leads/Show', [
            'lead' => $lead,
            'statuses' => LeadStatus::options(),
        ]);
    }

    public function updateStatus(Request $request, Lead $lead)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_column(LeadStatus::cases(), 'value'))],
        ]);

        $lead->update($data);

        return back()->with('success', 'Lead status updated.');
    }

    public function storeNote(Request $request, Lead $lead)
    {
        $data = $request->validate(['body' => ['required', 'string', 'max:5000']]);

        $lead->notes()->create(['user_id' => $request->user()->id, 'body' => $data['body']]);

        return back()->with('success', 'Note added.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }

    public function export(): StreamedResponse
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="leads.csv"'];

        return response()->stream(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Name', 'Email', 'Phone', 'Company', 'Service', 'Budget', 'Status', 'Message', 'Created']);
            Lead::with('service:id,title')->latest()->chunk(200, function ($chunk) use ($out) {
                foreach ($chunk as $l) {
                    fputcsv($out, [
                        $l->name, $l->email, $l->phone, $l->company,
                        $l->service?->title, $l->budget, $l->status->value,
                        str_replace(["\r", "\n"], ' ', $l->message), $l->created_at,
                    ]);
                }
            });
            fclose($out);
        }, 200, $headers);
    }
}
