<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile — {{ $gym->name }} — OnePazz Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-4xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Edit Profile</h1>
        <p class="text-sm text-gray-400 mt-1">Update your gym listing information</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('gym-portal.profile.update') }}" enctype="multipart/form-data">
        @csrf @method('PATCH')

        {{-- Basic Info --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name (English)</label>
                    <input type="text" name="name" value="{{ old('name', $gym->name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name (Khmer)</label>
                    <input type="text" name="name_kh" value="{{ old('name_kh', $gym->name_kh) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $gym->phone) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $gym->email) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                    <input type="url" name="website" value="{{ old('website', $gym->website) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" name="address_line1" value="{{ old('address_line1', $gym->address_line1) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                    <input type="text" name="district" value="{{ old('district', $gym->district) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $gym->city) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Daily Capacity Limit</label>
                    <input type="number" name="daily_capacity_limit" value="{{ old('daily_capacity_limit', $gym->daily_capacity_limit) }}" min="1" placeholder="Leave empty for unlimited" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
            </div>
            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (English)</label>
                    <textarea name="description" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('description', $gym->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Khmer)</label>
                    <textarea name="description_kh" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">{{ old('description_kh', $gym->description_kh) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Cover Image --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Cover Image</h3>
            @if($gym->cover_image_url)
            <img src="{{ $gym->cover_image_url }}" class="w-full h-40 object-cover rounded-lg mb-3">
            @endif
            <input type="file" name="cover_image" accept="image/*" class="text-sm text-gray-600">
            <p class="text-xs text-gray-400 mt-1">Max 4MB. JPEG, PNG or WebP.</p>
        </div>

        {{-- Activity Types --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Activity Types</h3>
            @php
                $allActivities = ['Yoga','Muay Thai','CrossFit','Swimming','Cycling','Boxing','Pilates','HIIT','Weightlifting','Zumba','Martial Arts','Dance'];
                $selected = $gym->activity_types ?? [];
            @endphp
            <div class="flex flex-wrap gap-2">
                @foreach($allActivities as $act)
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="activity_types[]" value="{{ $act }}" {{ in_array($act, $selected) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600">
                    <span class="text-sm text-gray-700">{{ $act }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Amenities --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Amenities</h3>
            @php
                $allAmenities = ['Parking','Showers','Lockers','AC','Wifi','Sauna','Pool','Cafe','Personal Training','Pro Shop'];
                $selectedAm = $gym->amenities ?? [];
            @endphp
            <div class="flex flex-wrap gap-2">
                @foreach($allAmenities as $am)
                <label class="flex items-center gap-1.5 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="{{ $am }}" {{ in_array($am, $selectedAm) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600">
                    <span class="text-sm text-gray-700">{{ $am }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Operating Hours --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Operating Hours</h3>
            @php
                $days = ['mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday','sun'=>'Sunday'];
                $hours = $gym->operating_hours ?? [];
            @endphp
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 text-gray-400 font-medium">Day</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Open</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Close</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Closed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $key => $label)
                    @php $h = $hours[$key] ?? ['open'=>'08:00','close'=>'21:00','closed'=>false]; @endphp
                    <tr class="border-b border-gray-50">
                        <td class="py-2 font-medium text-gray-700">{{ $label }}</td>
                        <td class="py-2"><input type="time" name="hours[{{ $key }}][open]" value="{{ $h['open'] ?? '08:00' }}" class="border border-gray-200 rounded-lg px-2 py-1 text-sm"></td>
                        <td class="py-2"><input type="time" name="hours[{{ $key }}][close]" value="{{ $h['close'] ?? '21:00' }}" class="border border-gray-200 rounded-lg px-2 py-1 text-sm"></td>
                        <td class="py-2"><input type="checkbox" name="hours[{{ $key }}][closed]" value="1" {{ ($h['closed'] ?? false) ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">Save Changes</button>
            <a href="{{ route('gym-portal.index') }}" class="border border-gray-200 px-6 py-2 rounded-lg hover:bg-gray-50 transition text-sm text-gray-600">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
