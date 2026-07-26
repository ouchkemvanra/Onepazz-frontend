<x-layouts.admin title="Add New Gym">

<a href="{{ route('admin.gyms.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Gyms
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Add New Gym</h1>
    <p class="text-sm text-gray-400 mt-1">Manually create a new partner gym</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.gyms.store') }}" x-data="{ createAdmin: {{ old('create_gym_admin') ? 'true' : 'false' }} }">
    @csrf

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Name (English) *</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="input">
            </div>
            <div>
                <label class="label">Name (Khmer)</label>
                <input type="text" name="name_kh" value="{{ old('name_kh') }}" class="input">
            </div>
            <div class="sm:col-span-2">
                <label class="label">Description</label>
                <textarea name="description" rows="3" class="input">{{ old('description') }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="label">Description (Khmer)</label>
                <textarea name="description_kh" rows="2" class="input">{{ old('description_kh') }}</textarea>
            </div>
        </div>
    </div>

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Location</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
                <label class="label">Address</label>
                <input type="text" name="address_line1" value="{{ old('address_line1') }}" class="input">
            </div>
            <div>
                <label class="label">District</label>
                <input type="text" name="district" value="{{ old('district') }}" class="input">
            </div>
            <div>
                <label class="label">City</label>
                <select name="city" class="input">
                    @foreach(['Phnom Penh','Banteay Meanchey','Battambang','Kampong Cham','Kampong Chhnang','Kampong Speu','Kampong Thom','Kampot','Kandal','Kep','Koh Kong','Kratie','Mondulkiri','Oddar Meanchey','Pailin','Preah Sihanouk','Preah Vihear','Prey Veng','Pursat','Ratanakiri','Siem Reap','Stung Treng','Svay Rieng','Takeo','Tbong Khmum'] as $city)
                    <option value="{{ $city }}" {{ old('city', 'Phnom Penh') === $city ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Province</label>
                <input type="text" name="province" value="{{ old('province') }}" class="input">
            </div>
            <div>
                <label class="label">Latitude</label>
                <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude') }}" placeholder="11.5564" class="input font-mono">
            </div>
            <div>
                <label class="label">Longitude</label>
                <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude') }}" placeholder="104.9282" class="input font-mono">
            </div>
        </div>
    </div>

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Contact & Activity</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div>
                <label class="label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="input">
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="input">
            </div>
            <div class="sm:col-span-2">
                <label class="label">Website</label>
                <input type="url" name="website" value="{{ old('website') }}" placeholder="https://" class="input">
            </div>
        </div>

        <label class="label">Activity Types</label>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-5">
            @foreach(['Gym & Weights','Yoga','Swimming','Muay Thai','Boxing','Pilates','CrossFit','Dance','Spa & Wellness','Other'] as $type)
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="activity_types[]" value="{{ $type }}"
                    {{ in_array($type, old('activity_types', [])) ? 'checked' : '' }}
                    class="rounded border-gray-300 accent-brand-500">
                {{ $type }}
            </label>
            @endforeach
        </div>

        <label class="label">Amenities</label>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
            @foreach(['Parking','Lockers','Showers','Pool','Sauna','Café','WiFi','Air Con','Towels','Personal Training'] as $amenity)
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="amenities[]" value="{{ $amenity }}"
                    {{ in_array($amenity, old('amenities', [])) ? 'checked' : '' }}
                    class="rounded border-gray-300 accent-brand-500">
                {{ $amenity }}
            </label>
            @endforeach
        </div>
    </div>

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Operating Hours</h3>
        <div class="space-y-2">
            @foreach(['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'] as $key => $label)
            <div class="flex items-center gap-4">
                <div class="w-24 text-sm text-gray-600">{{ $label }}</div>
                <input type="time" name="hours[{{ $key }}][open]" value="{{ old("hours.{$key}.open", '08:00') }}"
                    class="border border-gray-200 rounded-xl px-3 py-1.5 text-sm font-mono focus:ring-2 focus:ring-brand-400 focus:border-transparent outline-none">
                <span class="text-gray-400 text-sm">–</span>
                <input type="time" name="hours[{{ $key }}][close]" value="{{ old("hours.{$key}.close", '21:00') }}"
                    class="border border-gray-200 rounded-xl px-3 py-1.5 text-sm font-mono focus:ring-2 focus:ring-brand-400 focus:border-transparent outline-none">
                <label class="flex items-center gap-1.5 text-sm text-gray-500 cursor-pointer">
                    <input type="checkbox" name="hours[{{ $key }}][closed]" value="1"
                        {{ old("hours.{$key}.closed") ? 'checked' : '' }}
                        class="rounded border-gray-300 accent-brand-500">
                    Closed
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card p-6 mb-5">
        <h3 class="font-bold text-gray-900 mb-4">Partner Settings</h3>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div>
                <label class="label">Tier *</label>
                <select name="tier" required class="input">
                    @foreach(['bronze','silver','gold'] as $t)
                    <option value="{{ $t }}" {{ old('tier', 'bronze') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Status *</label>
                <select name="status" required class="input">
                    @foreach(['pending','active','suspended'] as $s)
                    <option value="{{ $s }}" {{ old('status', 'active') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Partner Since</label>
                <input type="date" name="partner_since" value="{{ old('partner_since', today()->toDateString()) }}" class="input">
            </div>
            <div>
                <label class="label">Monthly Fee (USD)</label>
                <input type="number" step="0.01" min="0" name="monthly_fee_usd" value="{{ old('monthly_fee_usd', '0') }}" class="input font-mono">
            </div>
            <div>
                <label class="label">OnePazz Cut %</label>
                <input type="number" step="0.01" min="0" max="100" name="revenue_share_pct" value="{{ old('revenue_share_pct', '30') }}" class="input font-mono">
            </div>
            <div>
                <label class="label">Daily Capacity</label>
                <input type="number" min="1" name="daily_capacity_limit" value="{{ old('daily_capacity_limit') }}" placeholder="Unlimited" class="input font-mono">
            </div>
            <div>
                <label class="label">Check-in Radius (m)</label>
                <input type="number" min="10" max="5000" name="checkin_radius_meters" value="{{ old('checkin_radius_meters', '50') }}" required class="input font-mono">
            </div>
        </div>
    </div>

    <div class="card p-6 mb-6">
        <h3 class="font-bold text-gray-900 mb-4">Gym Admin Account</h3>
        <label class="flex items-center gap-2 mb-4 cursor-pointer text-sm text-gray-700">
            <input type="checkbox" name="create_gym_admin" value="1" x-model="createAdmin"
                {{ old('create_gym_admin') ? 'checked' : '' }}
                class="rounded border-gray-300 accent-brand-500">
            Create gym admin account and send welcome email
        </label>
        <div x-show="createAdmin" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Admin Full Name</label>
                <input type="text" name="admin_full_name" value="{{ old('admin_full_name') }}" class="input">
            </div>
            <div>
                <label class="label">Admin Email</label>
                <input type="email" name="admin_user_email" value="{{ old('admin_user_email') }}" class="input">
                <p class="text-xs text-gray-400 mt-1">A temporary password will be generated and emailed.</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Create Gym</button>
        <a href="{{ route('admin.gyms.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

</x-layouts.admin>
