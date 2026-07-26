<x-layouts.gym-portal title="Classes">

<div class="flex items-center justify-between mb-6" x-data="{ showAdd: false }">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Classes</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $classes->count() }} class{{ $classes->count() !== 1 ? 'es' : '' }}</p>
    </div>
    <button @click="showAdd = !showAdd" class="btn-primary text-sm">
        + Add Class
    </button>
</div>

{{-- Add class form --}}
<div x-data="{ showAdd: false }">
<div x-show="showAdd" x-cloak class="card border-brand-200 p-6 mb-6">
    <h3 class="font-bold text-gray-900 mb-4">New Class</h3>
    <form method="POST" action="{{ route('gym-portal.classes.store') }}">
        @csrf
        @include('gym-portal._class-form', ['class' => null])
        <div class="flex gap-3 mt-5">
            <button type="submit" class="btn-primary text-sm">Create Class</button>
            <button type="button" @click="showAdd = false" class="btn-secondary text-sm">Cancel</button>
        </div>
    </form>
</div>

{{-- Classes list --}}
<div class="card p-6">
    @if($classes->isEmpty())
    <div class="text-center py-16">
        <div class="text-4xl mb-3">🏃</div>
        <p class="text-gray-500 font-medium">No classes yet</p>
        <p class="text-gray-400 text-sm mt-1">Add your first class to start taking bookings.</p>
        <button @click="showAdd = true" class="btn-primary text-sm mt-4">+ Add Class</button>
    </div>
    @else
    <div class="space-y-4">
        @foreach($classes as $class)
        <div x-data="{ editing: false }" class="border border-gray-100 rounded-xl p-5">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2.5 mb-2">
                        <span class="font-bold text-gray-900">{{ $class->name }}</span>
                        @if($class->is_active)
                            <span class="badge-green text-xs">Active</span>
                        @else
                            <span class="badge-gray text-xs">Inactive</span>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-gray-500">
                        @if($class->trainer_name)<span>👤 {{ $class->trainer_name }}</span>@endif
                        @php
                            $dayNames = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
                            $days = collect($class->day_of_week ?? [])->map(fn($d) => $dayNames[$d] ?? $d)->join(', ');
                        @endphp
                        <span>📅 {{ $days }}</span>
                        <span>⏰ {{ \Carbon\Carbon::parse($class->start_time)->format('g:i A') }}</span>
                        <span>⏱ {{ $class->duration_minutes }} min</span>
                        <span>👥 Max {{ $class->max_capacity }}</span>
                        <span class="text-brand-500 font-medium">✓ {{ $class->confirmedCount() }} confirmed</span>
                        @if($class->waitlistedCount() > 0)
                        <span class="text-warm-500 font-medium">⏳ {{ $class->waitlistedCount() }} waitlisted</span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-2 ml-4 shrink-0">
                    <button @click="editing = !editing" class="btn-secondary text-xs py-1.5 px-3">Edit</button>
                    <form method="POST" action="{{ route('gym-portal.classes.toggle', $class) }}">
                        @csrf
                        <button type="submit" class="btn-ghost text-xs py-1.5 px-3">
                            {{ $class->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>

            <div x-show="editing" x-cloak class="mt-5 border-t border-gray-100 pt-5">
                <form method="POST" action="{{ route('gym-portal.classes.update', $class) }}">
                    @csrf @method('PATCH')
                    @include('gym-portal._class-form', ['class' => $class])
                    <div class="flex gap-3 mt-4">
                        <button type="submit" class="btn-primary text-sm">Save Changes</button>
                        <button type="button" @click="editing = false" class="btn-secondary text-sm">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
</div>

</x-layouts.gym-portal>
