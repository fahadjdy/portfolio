@component('mail::message')
# New Project Inquiry

You received a new inquiry from your portfolio.

**Name:** {{ $lead->name }}
**Email:** {{ $lead->email }}
@if($lead->phone)
**Phone:** {{ $lead->phone }}
@endif
@if($lead->company)
**Company:** {{ $lead->company }}
@endif
@if($lead->subject)
**Subject:** {{ $lead->subject }}
@endif
@if($lead->service)
**Service:** {{ $lead->service->title }}
@endif
@if($lead->budget)
**Budget:** {{ $lead->budget }}
@endif

**Message:**

{{ $lead->message }}

@component('mail::button', ['url' => config('app.url').'/admin/leads/'.$lead->id])
View in admin
@endcomponent

Received {{ $lead->created_at->format('d M Y, H:i') }} · IP {{ $lead->ip_address }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
