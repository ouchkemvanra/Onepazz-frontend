@php $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Class Name (English) *</label>
        <input type="text" name="name" value="{{ old('name', $class?->name) }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Trainer</label>
        <input type="text" name="trainer_name" value="{{ old('trainer_name', $class?->trainer_name) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
        <input type="text" name="class_type" value="{{ old('class_type', $class?->class_type) }}" placeholder="e.g. Yoga, HIIT..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Start Time *</label>
        <input type="time" name="start_time" value="{{ old('start_time', $class ? \Carbon\Carbon::parse($class->start_time)->format('H:i') : '') }}" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes) *</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $class?->duration_minutes ?? 60) }}" min="15" max="480" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Max Capacity *</label>
        <input type="number" name="max_capacity" value="{{ old('max_capacity', $class?->max_capacity ?? 20) }}" min="1" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
    </div>
</div>
<div class="mt-4">
    <label class="block text-sm font-medium text-gray-700 mb-2">Days of Week *</label>
    <div class="flex flex-wrap gap-2">
        @foreach($days as $i => $dayLabel)
        @php $checked = $class ? in_array($i, (array)($class->day_of_week ?? [])) : false; @endphp
        <label class="flex items-center gap-1.5 cursor-pointer">
            <input type="checkbox" name="day_of_week[]" value="{{ $i }}" {{ $checked ? 'checked' : '' }} class="rounded border-gray-300 text-teal-600">
            <span class="text-sm text-gray-700">{{ $dayLabel }}</span>
        </label>
        @endforeach
    </div>
</div>
