<x-layouts.gym-portal title="Gym Profile">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Edit Profile</h1>
    <p class="text-sm text-gray-400 mt-1">Update your gym listing information</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('gym-portal.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf @method('PATCH')

    {{-- Basic info --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Basic Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="label">Name (English)</label>
                <input type="text" name="name" value="{{ old('name', $gym->name) }}" required class="input">
            </div>
            <div>
                <label class="label">Name (Khmer)</label>
                <input type="text" name="name_kh" value="{{ old('name_kh', $gym->name_kh) }}" class="input">
            </div>
            <div>
                <label class="label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $gym->phone) }}" class="input">
            </div>
            <div>
                <label class="label">Email</label>
                <input type="email" name="email" value="{{ old('email', $gym->email) }}" class="input">
            </div>
            <div class="sm:col-span-2">
                <label class="label">Website</label>
                <input type="url" name="website" value="{{ old('website', $gym->website) }}" class="input">
            </div>
            <div>
                <label class="label">Address</label>
                <input type="text" name="address_line1" value="{{ old('address_line1', $gym->address_line1) }}" class="input">
            </div>
            <div>
                <label class="label">District</label>
                <input type="text" name="district" value="{{ old('district', $gym->district) }}" class="input">
            </div>
            <div>
                <label class="label">City</label>
                <input type="text" name="city" value="{{ old('city', $gym->city) }}" class="input">
            </div>
            <div>
                <label class="label">Daily Capacity Limit</label>
                <input type="number" name="daily_capacity_limit" value="{{ old('daily_capacity_limit', $gym->daily_capacity_limit) }}"
                    min="1" placeholder="Leave empty for unlimited" class="input">
            </div>
            <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Description (English)</label>
                    <textarea name="description" rows="3" class="input">{{ old('description', $gym->description) }}</textarea>
                </div>
                <div>
                    <label class="label">Description (Khmer)</label>
                    <textarea name="description_kh" rows="3" class="input">{{ old('description_kh', $gym->description_kh) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Cover image --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Cover Image</h3>
        @if($gym->cover_image_url)
        <img src="{{ $gym->cover_image_url }}" class="w-full h-40 object-cover rounded-xl mb-3">
        @endif
        <input type="file" name="cover_image" accept="image/*" class="text-sm text-gray-600">
        <p class="text-xs text-gray-400 mt-1">Max 4MB. JPEG, PNG or WebP.</p>
    </div>

    {{-- Activities --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Activity Types</h3>
        @php
            $allActivities = ['Yoga','Muay Thai','CrossFit','Swimming','Cycling','Boxing','Pilates','HIIT','Weightlifting','Zumba','Martial Arts','Dance'];
            $selected = $gym->activity_types ?? [];
        @endphp
        <div class="flex flex-wrap gap-3">
            @foreach($allActivities as $act)
            <label class="flex items-center gap-2 cursor-pointer hover:text-gray-900 text-sm text-gray-600">
                <input type="checkbox" name="activity_types[]" value="{{ $act }}"
                    {{ in_array($act, $selected) ? 'checked' : '' }}
                    class="rounded border-gray-300 accent-brand-500 w-4 h-4">
                {{ $act }}
            </label>
            @endforeach
        </div>
    </div>

    {{-- Amenities --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Amenities</h3>
        @php
            $allAmenities = ['Parking','Showers','Lockers','AC','Wifi','Sauna','Pool','Cafe','Personal Training','Pro Shop'];
            $selectedAm = $gym->amenities ?? [];
        @endphp
        <div class="flex flex-wrap gap-3">
            @foreach($allAmenities as $am)
            <label class="flex items-center gap-2 cursor-pointer hover:text-gray-900 text-sm text-gray-600">
                <input type="checkbox" name="amenities[]" value="{{ $am }}"
                    {{ in_array($am, $selectedAm) ? 'checked' : '' }}
                    class="rounded border-gray-300 accent-brand-500 w-4 h-4">
                {{ $am }}
            </label>
            @endforeach
        </div>
    </div>

    {{-- Operating hours --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Operating Hours</h3>
        @php
            $days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'];
            $hours = $gym->operating_hours ?? [];
        @endphp
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left py-2 text-gray-400 font-semibold">Day</th>
                    <th class="text-left py-2 text-gray-400 font-semibold">Open</th>
                    <th class="text-left py-2 text-gray-400 font-semibold">Close</th>
                    <th class="text-left py-2 text-gray-400 font-semibold">Closed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($days as $key => $label)
                @php $h = $hours[$key] ?? ['open'=>'08:00','close'=>'21:00','closed'=>false]; @endphp
                <tr class="border-b border-gray-50">
                    <td class="py-2.5 font-medium text-gray-700">{{ $label }}</td>
                    <td class="py-2.5"><input type="time" name="hours[{{ $key }}][open]" value="{{ $h['open'] ?? '08:00' }}" class="input text-sm py-1.5 w-28"></td>
                    <td class="py-2.5"><input type="time" name="hours[{{ $key }}][close]" value="{{ $h['close'] ?? '21:00' }}" class="input text-sm py-1.5 w-28"></td>
                    <td class="py-2.5"><input type="checkbox" name="hours[{{ $key }}][closed]" value="1" {{ ($h['closed'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 accent-brand-500 w-4 h-4"></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="{{ route('gym-portal.index') }}" class="btn-secondary">Cancel</a>
    </div>
</form>

</x-layouts.gym-portal>
