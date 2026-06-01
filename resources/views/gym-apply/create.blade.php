<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner with KhmerFit — Apply Now</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Browse Gyms</a>
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">Sign In</a>
        </div>
    </div>
</nav>

{{-- HERO --}}
<div class="bg-gradient-to-br from-gray-900 via-gray-800 to-teal-900 text-white py-20 px-6 text-center">
    <div class="inline-flex items-center gap-2 bg-teal-500/20 border border-teal-400/30 text-teal-300 text-xs font-medium px-4 py-1.5 rounded-full mb-6">
        🇰🇭 Cambodia's #1 Corporate Wellness Platform
    </div>
    <h1 class="text-4xl font-bold mb-4">Partner with KhmerFit</h1>
    <p class="text-gray-300 text-lg max-w-xl mx-auto mb-8">Connect your gym or fitness studio with hundreds of corporate members. Get steady revenue, zero customer acquisition costs.</p>
    <div class="flex justify-center gap-8 text-center">
        <div>
            <div class="text-2xl font-bold text-teal-400">500+</div>
            <div class="text-xs text-gray-400 mt-1">Corporate Members</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-teal-400">50+</div>
            <div class="text-xs text-gray-400 mt-1">Partner Gyms</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-teal-400">3–5</div>
            <div class="text-xs text-gray-400 mt-1">Day Review Time</div>
        </div>
    </div>
</div>

{{-- FORM --}}
<div class="max-w-2xl mx-auto px-6 py-12">

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('gym-apply.store') }}" class="space-y-8">
        @csrf

        {{-- Studio Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 mb-5">Studio Information</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Studio Name <span class="text-red-500">*</span></label>
                        <input type="text" name="studio_name" value="{{ old('studio_name') }}" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Studio Name (Khmer)</label>
                        <input type="text" name="studio_name_kh" value="{{ old('studio_name_kh') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="ឈ្មោះស្ទូឌីយ៉ូ">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Activity Types</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach(['Gym & Weights','Yoga','Swimming','Muay Thai','Boxing','Pilates','CrossFit','Dance','Spa & Wellness','Other'] as $type)
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="activity_types[]" value="{{ $type }}"
                                {{ in_array($type, old('activity_types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-teal-600">
                            {{ $type }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        placeholder="Tell us about your studio, facilities, and what makes you unique...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 mb-5">Contact Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Your Name <span class="text-red-500">*</span></label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone (optional)</label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+855 ..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-gray-800 mb-5">Location</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District <span class="text-red-500">*</span></label>
                        <input type="text" name="district" value="{{ old('district') }}" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City <span class="text-red-500">*</span></label>
                        <select name="city" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            @foreach(['Phnom Penh','Siem Reap','Sihanoukville','Battambang','Other'] as $city)
                            <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Terms --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}
                    class="mt-0.5 rounded border-gray-300 text-teal-600">
                <span class="text-sm text-gray-700">
                    I agree to KhmerFit's <a href="#" class="text-teal-600 hover:underline">Partner Terms & Conditions</a> and understand that my application will be reviewed within 3–5 business days. <span class="text-red-500">*</span>
                </span>
            </label>
        </div>

        <button type="submit"
            class="w-full bg-teal-600 text-white font-semibold py-3 px-6 rounded-xl hover:bg-teal-700 transition text-base">
            Submit Application →
        </button>
    </form>
</div>

{{-- FOOTER --}}
<div class="border-t border-gray-200 py-8 text-center">
    <p class="text-sm text-gray-400">Questions? Email us at <a href="mailto:partners@khmerfit.com.kh" class="text-teal-600">partners@khmerfit.com.kh</a></p>
</div>

</body>
</html>
