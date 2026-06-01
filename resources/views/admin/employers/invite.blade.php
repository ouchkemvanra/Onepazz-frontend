<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Employer — Admin — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-50">
@include('admin._nav')

<div class="max-w-2xl mx-auto px-6 py-8">
    <div class="mb-6"><a href="{{ route('admin.employers.index', ['tab'=>'invitations']) }}" class="text-sm text-teal-600 hover:text-teal-700">← Back to Invitations</a></div>
    <h1 class="text-2xl font-bold text-gray-800 mb-1">Send Employer Invitation</h1>
    <p class="text-sm text-gray-400 mb-8">Invite a company to register on KhmerFit. They'll receive a personalised link valid for 14 days.</p>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">@foreach($errors->all() as $e)<li class="text-sm">{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.employers.invite.send') }}">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 space-y-4">
            <h3 class="font-semibold">Contact Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Company Name <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="Pre-fill on the registration form">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 space-y-4">
            <h3 class="font-semibold">Suggested Plan <span class="text-gray-400 font-normal text-sm">(optional)</span></h3>
            <p class="text-xs text-gray-400">Pre-select a plan in the registration form. The company can still change it.</p>
            <select name="suggested_plan_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">— No suggestion —</option>
                @foreach($plans as $plan)
                <option value="{{ $plan->id }}" {{ old('suggested_plan_id') == $plan->id ? 'selected' : '' }}>
                    {{ $plan->name }} — ${{ number_format($plan->price_usd, 2) }}/employee/mo
                </option>
                @endforeach
            </select>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Personal Message <span class="text-gray-400 font-normal">(optional)</span></label>
            <textarea name="personal_message" rows="4" placeholder="Add a personal note to the invitation email..."
                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('personal_message') }}</textarea>
            <p class="text-xs text-gray-400 mt-1">This will appear in the invitation email beneath the standard text.</p>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 font-medium">Send Invitation</button>
            <a href="{{ route('admin.employers.index', ['tab'=>'invitations']) }}" class="border border-gray-200 px-6 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
