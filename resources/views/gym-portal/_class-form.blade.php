@php $days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']; @endphp
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="label">Class Name (English) *</label>
        <input type="text" name="name" value="{{ old('name', $class?->name) }}" required class="input">
    </div>
    <div>
        <label class="label">Trainer</label>
        <input type="text" name="trainer_name" value="{{ old('trainer_name', $class?->trainer_name) }}" class="input">
    </div>
    <div>
        <label class="label">Type</label>
        <input type="text" name="class_type" value="{{ old('class_type', $class?->class_type) }}" placeholder="e.g. Yoga, HIIT..." class="input">
    </div>
    <div>
        <label class="label">Start Time *</label>
        <input type="time" name="start_time" value="{{ old('start_time', $class ? \Carbon\Carbon::parse($class->start_time)->format('H:i') : '') }}" required class="input">
    </div>
    <div>
        <label class="label">Duration (minutes) *</label>
        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $class?->duration_minutes ?? 60) }}" min="15" max="480" required class="input font-mono">
    </div>
    <div>
        <label class="label">Max Capacity *</label>
        <input type="number" name="max_capacity" value="{{ old('max_capacity', $class?->max_capacity ?? 20) }}" min="1" required class="input font-mono">
    </div>
</div>
<div class="mt-4">
    <label class="label">Days of Week *</label>
    <div class="flex flex-wrap gap-3 mt-2">
        @foreach($days as $i => $dayLabel)
        @php $checked = $class ? in_array($i, (array)($class->day_of_week ?? [])) : false; @endphp
        <label class="flex items-center gap-1.5 cursor-pointer text-sm text-gray-700">
            <input type="checkbox" name="day_of_week[]" value="{{ $i }}" {{ $checked ? 'checked' : '' }} class="rounded border-gray-300 accent-brand-500">
            {{ $dayLabel }}
        </label>
        @endforeach
    </div>
</div>
