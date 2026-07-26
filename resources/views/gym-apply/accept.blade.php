<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center">
            <x-logo variant="dark" size="32" />
        </a>
    </div>
</nav>

<div class="max-w-2xl mx-auto px-6 py-12">

    <div class="text-center mb-10">
        <div class="inline-flex items-center gap-2 bg-brand-50 border border-brand-200 text-brand-700 text-xs font-medium px-4 py-1.5 rounded-full mb-4">
            ✉️ You've been invited
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Partner Application</h1>
        <p class="text-gray-500 text-sm">Your invitation expires on {{ $application->invite_expires_at->format('d M Y') }}.</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('gym-apply.submit', $token) }}" class="space-y-5">
        @csrf

        <div class="card p-6 space-y-4">
            <h2 class="font-bold text-gray-900">Studio Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="label">Studio Name *</label><input type="text" name="studio_name" value="{{ old('studio_name', $application->studio_name) }}" required class="input"></div>
                <div><label class="label">Studio Name (Khmer)</label><input type="text" name="studio_name_kh" value="{{ old('studio_name_kh', $application->studio_name_kh) }}" class="input"></div>
            </div>

            <div>
                <label class="label">Activity Types</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
                    @foreach(['Gym & Weights','Yoga','Swimming','Muay Thai','Boxing','Pilates','CrossFit','Dance','Spa & Wellness','Other'] as $type)
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="activity_types[]" value="{{ $type }}"
                            {{ in_array($type, old('activity_types', $application->activity_types ?? [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 accent-brand-500">
                        {{ $type }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div><label class="label">Description</label><textarea name="description" rows="3" class="input">{{ old('description', $application->description) }}</textarea></div>
            <div><label class="label">Website</label><input type="url" name="website" value="{{ old('website', $application->website) }}" placeholder="https://" class="input"></div>
        </div>

        <div class="card p-6 space-y-4">
            <h2 class="font-bold text-gray-900">Contact & Location</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="label">Your Name *</label><input type="text" name="contact_name" value="{{ old('contact_name', $application->contact_name) }}" required class="input"></div>
                <div><label class="label">Phone</label><input type="text" name="contact_phone" value="{{ old('contact_phone', $application->contact_phone) }}" class="input" placeholder="+855 ..."></div>
                <div class="sm:col-span-2"><label class="label">Address *</label><input type="text" name="address" value="{{ old('address', $application->address) }}" required class="input"></div>
                <div><label class="label">District *</label><input type="text" name="district" value="{{ old('district', $application->district) }}" required class="input"></div>
                <div>
                    <label class="label">City *</label>
                    <select name="city" required class="input">
                        <option value="">— Select —</option>
                        @foreach(['Phnom Penh','Banteay Meanchey','Battambang','Kampong Cham','Kampong Chhnang','Kampong Speu','Kampong Thom','Kampot','Kandal','Kep','Koh Kong','Kratie','Mondulkiri','Oddar Meanchey','Pailin','Preah Sihanouk','Preah Vihear','Prey Veng','Pursat','Ratanakiri','Siem Reap','Stung Treng','Svay Rieng','Takeo','Tbong Khmum'] as $city)
                        <option value="{{ $city }}" {{ old('city', $application->city) === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3 text-base">Submit Profile →</button>
    </form>
</div>
</body>
</html>
