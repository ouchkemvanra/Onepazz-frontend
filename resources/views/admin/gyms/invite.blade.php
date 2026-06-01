<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Gym Invitation — Admin — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('admin._nav')

<div class="max-w-2xl mx-auto px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('admin.gyms.invitations') }}" class="text-sm text-teal-600 hover:text-teal-700">← Back to Invitations</a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Send Gym Invitation</h1>
        <p class="text-sm text-gray-400 mt-1">Invite a gym owner to join KhmerFit as a partner</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $e)<li class="text-sm">{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.gyms.invite.send') }}">
        @csrf
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Studio Name <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="text" name="studio_name" value="{{ old('studio_name') }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tier Offered <span class="text-red-500">*</span></label>
                <select name="tier" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    @foreach(['bronze','silver','gold'] as $t)
                    <option value="{{ $t }}" {{ old('tier', 'bronze') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-400 mt-1">The partner will see this in the invitation email.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Personal Message <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="personal_message" rows="4"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                    placeholder="Add a personal note to appear in the email...">{{ old('personal_message') }}</textarea>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                <p class="text-xs text-blue-700">The invitation link will expire in <strong>7 days</strong>. You can resend it from the Invitations list.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition font-medium text-sm">
                    Send Invitation
                </button>
                <a href="{{ route('admin.gyms.invitations') }}" class="border border-gray-200 px-6 py-2 rounded-lg hover:bg-gray-50 transition text-sm text-gray-600">
                    Cancel
                </a>
            </div>
        </div>
    </form>
</div>
</body>
</html>
