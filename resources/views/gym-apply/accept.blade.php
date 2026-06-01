<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accept Invitation — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit
        </a>
    </div>
</nav>

<div class="max-w-2xl mx-auto px-6 py-12">

    <div class="text-center mb-10">
        <div class="inline-flex items-center gap-2 bg-teal-50 border border-teal-200 text-teal-700 text-xs font-medium px-4 py-1.5 rounded-full mb-4">
            ✉️ You've been invited
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Complete Your Partner Application</h1>
        <p class="text-gray-500 text-sm">Your invitation expires on {{ $application->invite_expires_at->format('d M Y') }}.</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('gym-apply.submit', $token) }}" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 mb-2">Studio Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Studio Name <span class="text-red-500">*</span></label>
                    <input type="text" name="studio_name" value="{{ old('studio_name', $application->studio_name) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Studio Name (Khmer)</label>
                    <input type="text" name="studio_name_kh" value="{{ old('studio_name_kh', $application->studio_name_kh) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Activity Types</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach(['Gym & Weights','Yoga','Swimming','Muay Thai','Boxing','Pilates','CrossFit','Dance','Spa & Wellness','Other'] as $type)
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="activity_types[]" value="{{ $type }}"
                            {{ in_array($type, old('activity_types', $application->activity_types ?? [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-teal-600">
                        {{ $type }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('description', $application->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                <input type="url" name="website" value="{{ old('website', $application->website) }}" placeholder="https://"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800 mb-2">Contact & Location</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name', $application->contact_name) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone', $application->contact_phone) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="+855 ...">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address', $application->address) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District <span class="text-red-500">*</span></label>
                    <input type="text" name="district" value="{{ old('district', $application->district) }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                    <select name="city" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">— Select —</option>
                        @foreach(['Phnom Penh','Banteay Meanchey','Battambang','Kampong Cham','Kampong Chhnang','Kampong Speu','Kampong Thom','Kampot','Kandal','Kep','Koh Kong','Kratie','Mondulkiri','Oddar Meanchey','Pailin','Preah Sihanouk','Preah Vihear','Prey Veng','Pursat','Ratanakiri','Siem Reap','Stung Treng','Svay Rieng','Takeo','Tbong Khmum'] as $city)
                        <option value="{{ $city }}" {{ old('city', $application->city) === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button type="submit"
            class="w-full bg-teal-600 text-white font-semibold py-3 rounded-xl hover:bg-teal-700 transition">
            Submit Profile →
        </button>
    </form>
</div>
</body>
</html>
