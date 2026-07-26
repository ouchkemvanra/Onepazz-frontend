<x-layouts.admin title="Send Gym Invitation">

<a href="{{ route('admin.gyms.invitations') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Invitations
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Send Gym Invitation</h1>
    <p class="text-sm text-gray-400 mt-1">Invite a gym owner to join as a partner</p>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6 text-sm">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.gyms.invite.send') }}">
        @csrf
        <div class="card p-6 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Contact Name *</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="input">
                </div>
                <div>
                    <label class="label">Email Address *</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="input">
                </div>
            </div>

            <div>
                <label class="label">Studio Name <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="studio_name" value="{{ old('studio_name') }}" class="input">
            </div>

            <div>
                <label class="label">Tier Offered *</label>
                <select name="tier" required class="input">
                    @foreach(['bronze','silver','gold'] as $t)
                    <option value="{{ $t }}" {{ old('tier', 'bronze') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">The partner will see this in the invitation email.</p>
            </div>

            <div>
                <label class="label">Personal Message <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="personal_message" rows="4" class="input" placeholder="Add a personal note to appear in the email...">{{ old('personal_message') }}</textarea>
            </div>

            <div class="bg-brand-50 border border-brand-200 rounded-xl p-3">
                <p class="text-xs text-brand-700">The invitation link will expire in <strong>7 days</strong>. You can resend it from the Invitations list.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Send Invitation</button>
                <a href="{{ route('admin.gyms.invitations') }}" class="btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

</x-layouts.admin>
