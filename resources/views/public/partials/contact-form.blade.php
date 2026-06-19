@php($services = $services ?? collect())
<form method="POST" action="{{ route('contact.store') }}" class="space-y-5" x-data="{ sending: false }" @submit="sending = true">
    @csrf

    {{-- Honeypot: hidden from humans; bots that fill it are rejected (prohibited rule). --}}
    <div class="hidden" aria-hidden="true">
        <label>Leave this empty
            <input type="text" name="website" tabindex="-1" autocomplete="off" value="{{ old('website') }}">
        </label>
    </div>

    <div class="grid gap-5 sm:grid-cols-2">
        <div>
            <label for="name" class="mb-1.5 block text-sm font-medium text-slate-700">Name <span class="text-rose-500">*</span></label>
            <input id="name" name="name" type="text" required value="{{ old('name') }}"
                   class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
            @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Email <span class="text-rose-500">*</span></label>
            <input id="email" name="email" type="email" required value="{{ old('email') }}"
                   class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
            @error('email')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">Phone</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                   class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
            @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="company" class="mb-1.5 block text-sm font-medium text-slate-700">Company</label>
            <input id="company" name="company" type="text" value="{{ old('company') }}"
                   class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
        </div>
        <div>
            <label for="service_id" class="mb-1.5 block text-sm font-medium text-slate-700">What do you need?</label>
            <select id="service_id" name="service_id"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
                <option value="">Select a service…</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="budget" class="mb-1.5 block text-sm font-medium text-slate-700">Budget (optional)</label>
            <input id="budget" name="budget" type="text" value="{{ old('budget') }}" placeholder="e.g. ₹50k–₹1L"
                   class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
        </div>
    </div>

    <div>
        <label for="message" class="mb-1.5 block text-sm font-medium text-slate-700">Project details <span class="text-rose-500">*</span></label>
        <textarea id="message" name="message" rows="5" required
                  class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">{{ old('message') }}</textarea>
        @error('message')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="sending">
        <span x-show="!sending">Send message</span>
        <span x-show="sending" x-cloak>Sending…</span>
        <x-icon name="arrow-right" class="h-4 w-4" x-show="!sending" />
    </button>
</form>
