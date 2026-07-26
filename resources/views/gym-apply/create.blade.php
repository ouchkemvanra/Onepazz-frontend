<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner with OnePazz — Apply Now</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center">
            <x-logo variant="dark" size="32" />
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Browse Gyms</a>
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">Sign In</a>
        </div>
    </div>
</nav>

<div class="bg-gradient-to-br from-gray-900 via-gray-800 to-brand-900 text-white py-20 px-6 text-center">
    <div class="inline-flex items-center gap-2 bg-brand-500/20 border border-brand-400/30 text-brand-300 text-xs font-medium px-4 py-1.5 rounded-full mb-6">
        🇰🇭 Cambodia's #1 Corporate Wellness Platform
    </div>
    <h1 class="text-4xl font-bold mb-4">Partner with OnePazz</h1>
    <p class="text-gray-300 text-lg max-w-xl mx-auto mb-8">Connect your gym or fitness studio with hundreds of corporate members. Get steady revenue, zero customer acquisition costs.</p>
    <div class="flex justify-center gap-10 text-center">
        <div>
            <div class="text-2xl font-bold text-brand-400">500+</div>
            <div class="text-xs text-gray-400 mt-1">Corporate Members</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-brand-400">50+</div>
            <div class="text-xs text-gray-400 mt-1">Partner Gyms</div>
        </div>
        <div>
            <div class="text-2xl font-bold text-brand-400">3–5</div>
            <div class="text-xs text-gray-400 mt-1">Day Review Time</div>
        </div>
    </div>
</div>

<div class="max-w-2xl mx-auto px-6 py-12">

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
        <ul class="list-disc list-inside space-y-1 text-sm">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('gym-apply.store') }}" class="space-y-6">
        @csrf

        <div class="card p-6">
            <h2 class="font-bold text-gray-900 mb-5">Studio Information</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Studio Name *</label>
                        <input type="text" name="studio_name" value="{{ old('studio_name') }}" required class="input">
                    </div>
                    <div>
                        <label class="label">Studio Name (Khmer)</label>
                        <input type="text" name="studio_name_kh" value="{{ old('studio_name_kh') }}" class="input" placeholder="ឈ្មោះស្ទូឌីយ៉ូ">
                    </div>
                </div>

                <div>
                    <label class="label">Activity Types</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
                        @foreach(['Gym & Weights','Yoga','Swimming','Muay Thai','Boxing','Pilates','CrossFit','Dance','Spa & Wellness','Other'] as $type)
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="activity_types[]" value="{{ $type }}"
                                {{ in_array($type, old('activity_types', [])) ? 'checked' : '' }}
                                class="rounded border-gray-300 accent-brand-500">
                            {{ $type }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="label">Description</label>
                    <textarea name="description" rows="3" class="input" placeholder="Tell us about your studio, facilities, and what makes you unique...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="label">Website</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://" class="input">
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-bold text-gray-900 mb-5">Contact Information</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Your Name *</label>
                    <input type="text" name="contact_name" value="{{ old('contact_name') }}" required class="input">
                </div>
                <div>
                    <label class="label">Email *</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" required class="input">
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Phone <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+855 ..." class="input">
                </div>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-bold text-gray-900 mb-5">Location</h2>
            <div class="space-y-4">
                <div>
                    <label class="label">Address *</label>
                    <input type="text" name="address" value="{{ old('address') }}" required class="input">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">District *</label>
                        <input type="text" name="district" value="{{ old('district') }}" required class="input">
                    </div>
                    <div>
                        <label class="label">City *</label>
                        <select name="city" required class="input">
                            <option value="">— Select city / province —</option>
                            @foreach(['Phnom Penh','Banteay Meanchey','Battambang','Kampong Cham','Kampong Chhnang','Kampong Speu','Kampong Thom','Kampot','Kandal','Kep','Koh Kong','Kratie','Mondulkiri','Oddar Meanchey','Pailin','Preah Sihanouk','Preah Vihear','Prey Veng','Pursat','Ratanakiri','Siem Reap','Stung Treng','Svay Rieng','Takeo','Tbong Khmum'] as $city)
                            <option value="{{ $city }}" {{ old('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-5">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="agree_terms" value="1" {{ old('agree_terms') ? 'checked' : '' }}
                    class="mt-0.5 rounded border-gray-300 accent-brand-500">
                <span class="text-sm text-gray-700">
                    I agree to OnePazz's <a href="{{ route('gym-apply.terms') }}" target="_blank" class="text-brand-600 hover:text-brand-700 font-medium">Partner Terms & Conditions</a> and understand that my application will be reviewed within 3–5 business days. <span class="text-red-500">*</span>
                </span>
            </label>
        </div>

        <button type="submit" class="btn-primary w-full justify-center py-3 text-base">Submit Application →</button>
    </form>
</div>

<div class="border-t border-gray-200 py-8 text-center">
    <p class="text-sm text-gray-400">Questions? Email us at <a href="mailto:partners@onepazz.com.kh" class="text-brand-600 hover:text-brand-700">partners@onepazz.com.kh</a></p>
</div>

</body>
</html>
