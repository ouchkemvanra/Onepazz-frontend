<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes — {{ $gym->name }} — KhmerFit Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-7xl mx-auto px-6 py-8" x-data="{ showAdd: false }">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Classes</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $classes->count() }} class{{ $classes->count() !== 1 ? 'es' : '' }}</p>
        </div>
        <button @click="showAdd = !showAdd" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">
            + Add Class
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Add Class Form --}}
    <div x-show="showAdd" x-cloak class="bg-white rounded-xl border border-teal-200 p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">New Class</h3>
        <form method="POST" action="{{ route('gym-portal.classes.store') }}">
            @csrf
            @include('gym-portal._class-form', ['class' => null])
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-teal-700 transition">Create Class</button>
                <button type="button" @click="showAdd = false" class="border border-gray-200 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Classes Table --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($classes->isEmpty())
        <p class="text-sm text-gray-400 text-center py-8">No classes yet. Add your first class above.</p>
        @else
        <div class="space-y-4">
            @foreach($classes as $class)
            <div x-data="{ editing: false }" class="border border-gray-100 rounded-xl p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-800">{{ $class->name }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $class->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $class->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-gray-500">
                            @if($class->trainer_name)<span>👤 {{ $class->trainer_name }}</span>@endif
                            @php
                                $dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                                $days = collect($class->day_of_week ?? [])->map(fn($d) => $dayNames[$d] ?? $d)->join(', ');
                            @endphp
                            <span>📅 {{ $days }}</span>
                            <span>⏰ {{ \Carbon\Carbon::parse($class->start_time)->format('g:i A') }}</span>
                            <span>⏱ {{ $class->duration_minutes }} min</span>
                            <span>👥 Max {{ $class->max_capacity }}</span>
                            <span class="text-teal-600">✓ {{ $class->confirmedCount() }} confirmed</span>
                            @if($class->waitlistedCount() > 0)<span class="text-orange-500">⏳ {{ $class->waitlistedCount() }} waitlisted</span>@endif
                        </div>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button @click="editing = !editing" class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">Edit</button>
                        <form method="POST" action="{{ route('gym-portal.classes.toggle', $class) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                                {{ $class->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Edit Form (inline) --}}
                <div x-show="editing" x-cloak class="mt-4 border-t border-gray-100 pt-4">
                    <form method="POST" action="{{ route('gym-portal.classes.update', $class) }}">
                        @csrf @method('PATCH')
                        @include('gym-portal._class-form', ['class' => $class])
                        <div class="flex gap-3 mt-4">
                            <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-teal-700 transition">Save</button>
                            <button type="button" @click="editing = false" class="border border-gray-200 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
</body>
</html>
