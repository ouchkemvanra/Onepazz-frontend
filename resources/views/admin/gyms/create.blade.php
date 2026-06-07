<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Gym — Admin — OnePazz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('admin._nav')

<div class="max-w-4xl mx-auto px-6 py-8">

    <div class="mb-6">
        <a href="{{ route('admin.gyms.index') }}" class="text-sm text-teal-600 hover:text-teal-700">← Back to Gyms</a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Add New Gym</h1>
        <p class="text-sm text-gray-400 mt-1">Manually create a new partner gym</p>
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

    <form method="POST" action="{{ route('admin.gyms.store') }}" x-data="{ createAdmin: {{ old('create_gym_admin') ? 'true' : 'false' }} }">
        @csrf

        {{-- Basic Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name (English) <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name (Khmer)</label>
                    <input type="text" name="name_kh" value="{{ old('name_kh') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('description') }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Khmer)</label>
                    <textarea name="description_kh" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('description_kh') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Location</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                    <input type="text" name="district" value="{{ old('district') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <select name="city" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['Phnom Penh','Banteay Meanchey','Battambang','Kampong Cham','Kampong Chhnang','Kampong Speu','Kampong Thom','Kampot','Kandal','Kep','Koh Kong','Kratie','Mondulkiri','Oddar Meanchey','Pailin','Preah Sihanouk','Preah Vihear','Prey Veng','Pursat','Ratanakiri','Siem Reap','Stung Treng','Svay Rieng','Takeo','Tbong Khmum'] as $city)
                        <option value="{{ $city }}" {{ old('city', 'Phnom Penh') === $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Province</label>
                    <input type="text" name="province" value="{{ old('province') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude') }}" placeholder="11.5564" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude') }}" placeholder="104.9282" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
            </div>
        </div>

        {{-- Contact & Activity --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Contact & Activity</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website') }}" placeholder="https://" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>

            <label class="block text-sm font-medium text-gray-700 mb-2">Activity Types</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">
                @foreach(['Gym & Weights','Yoga','Swimming','Muay Thai','Boxing','Pilates','CrossFit','Dance','Spa & Wellness','Other'] as $type)
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="activity_types[]" value="{{ $type }}"
                        {{ in_array($type, old('activity_types', [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-teal-600">
                    {{ $type }}
                </label>
                @endforeach
            </div>

            <label class="block text-sm font-medium text-gray-700 mb-2">Amenities</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach(['Parking','Lockers','Showers','Pool','Sauna','Café','WiFi','Air Con','Towels','Personal Training'] as $amenity)
                <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                        {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-teal-600">
                    {{ $amenity }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Operating Hours --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Operating Hours</h3>
            <div class="space-y-2">
                @foreach(['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'] as $key => $label)
                <div class="flex items-center gap-4">
                    <div class="w-24 text-sm text-gray-600">{{ $label }}</div>
                    <input type="time" name="hours[{{ $key }}][open]" value="{{ old("hours.{$key}.open", '08:00') }}"
                        class="border border-gray-200 rounded px-2 py-1 text-sm font-mono">
                    <span class="text-gray-400 text-sm">–</span>
                    <input type="time" name="hours[{{ $key }}][close]" value="{{ old("hours.{$key}.close", '21:00') }}"
                        class="border border-gray-200 rounded px-2 py-1 text-sm font-mono">
                    <label class="flex items-center gap-1.5 text-sm text-gray-500 cursor-pointer">
                        <input type="checkbox" name="hours[{{ $key }}][closed]" value="1"
                            {{ old("hours.{$key}.closed") ? 'checked' : '' }}
                            class="rounded border-gray-300">
                        Closed
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Partner Settings --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Partner Settings</h3>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tier <span class="text-red-500">*</span></label>
                    <select name="tier" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['bronze','silver','gold'] as $t)
                        <option value="{{ $t }}" {{ old('tier', 'bronze') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['pending','active','suspended'] as $s)
                        <option value="{{ $s }}" {{ old('status', 'active') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Partner Since</label>
                    <input type="date" name="partner_since" value="{{ old('partner_since', today()->toDateString()) }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Fee (USD)</label>
                    <input type="number" step="0.01" min="0" name="monthly_fee_usd" value="{{ old('monthly_fee_usd', '0') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OnePazz Cut %</label>
                    <input type="number" step="0.01" min="0" max="100" name="revenue_share_pct" value="{{ old('revenue_share_pct', '30') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Daily Capacity</label>
                    <input type="number" min="1" name="daily_capacity_limit" value="{{ old('daily_capacity_limit') }}"
                        placeholder="Unlimited" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Radius (m)</label>
                    <input type="number" min="10" max="5000" name="checkin_radius_meters" value="{{ old('checkin_radius_meters', '50') }}"
                        required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono">
                </div>
            </div>
        </div>

        {{-- Gym Admin Account --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold mb-4">Gym Admin Account</h3>
            <label class="flex items-center gap-3 cursor-pointer mb-4">
                <input type="checkbox" name="create_gym_admin" value="1" x-model="createAdmin"
                    {{ old('create_gym_admin') ? 'checked' : '' }}
                    class="rounded border-gray-300 text-teal-600">
                <span class="text-sm text-gray-700">Create gym admin account and send welcome email</span>
            </label>
            <div x-show="createAdmin" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Full Name</label>
                    <input type="text" name="admin_full_name" value="{{ old('admin_full_name') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Admin Email</label>
                    <input type="email" name="admin_user_email" value="{{ old('admin_user_email') }}"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">A temporary password will be generated and emailed.</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-teal-600 text-white px-8 py-2.5 rounded-lg hover:bg-teal-700 transition font-medium">
                Create Gym
            </button>
            <a href="{{ route('admin.gyms.index') }}" class="border border-gray-200 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm text-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>
</body>
</html>
