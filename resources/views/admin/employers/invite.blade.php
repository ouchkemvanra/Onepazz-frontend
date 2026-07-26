<x-layouts.admin title="Send Employer Invitation">

<a href="{{ route('admin.employers.index', ['tab'=>'invitations']) }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Invitations
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Send Employer Invitation</h1>
    <p class="text-sm text-gray-400 mt-1">Invite a company to register. They'll receive a personalised link valid for 14 days.</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.employers.invite.send') }}">
        @csrf

        <div class="card p-6 mb-5 space-y-4">
            <h3 class="font-bold text-gray-900">Contact Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Contact Name *</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="input">
                </div>
                <div>
                    <label class="label">Contact Email *</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="input">
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Company Name <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="input" placeholder="Pre-fill on the registration form">
                </div>
            </div>
        </div>

        <div class="card p-6 mb-5 space-y-4">
            <h3 class="font-bold text-gray-900">Suggested Plan <span class="text-gray-400 font-normal text-sm">(optional)</span></h3>
            <p class="text-xs text-gray-400">Pre-select a plan in the registration form. The company can still change it.</p>
            <select name="suggested_plan_id" class="input">
                <option value="">— No suggestion —</option>
                @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ old('suggested_plan_id') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }} — ${{ number_format($plan->price_usd, 2) }}/employee/mo
                </option>
                @endforeach
            </select>
        </div>

        <div class="card p-6 mb-6">
            <label class="label">Personal Message <span class="text-gray-400 font-normal">(optional)</span></label>
            <textarea name="personal_message" rows="4" placeholder="Add a personal note to the invitation email..." class="input">{{ old('personal_message') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">This will appear in the invitation email beneath the standard text.</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">Send Invitation</button>
            <a href="{{ route('admin.employers.index', ['tab'=>'invitations']) }}" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

</x-layouts.admin>
