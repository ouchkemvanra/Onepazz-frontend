<x-layouts.app :title="$gym->name">

{{-- Hero --}}
<div class="bg-gradient-to-br from-gray-900 via-gray-800 to-brand-900 text-white">
    <div class="max-w-6xl mx-auto px-6 py-10">
        <a href="{{ route('gyms.index') }}" class="inline-flex items-center gap-1.5 text-white/50 hover:text-white/80 text-sm mb-6 transition-colors">
            {{ __('gym.back_to_gyms') }}
        </a>
        <div class="flex gap-5 items-start">
            <div class="w-20 h-20 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-4xl flex-shrink-0 backdrop-blur-sm">
                @php
                    $acts = $gym->activity_types ?? [];
                    echo match(true) {
                        in_array('yoga', $acts)      => '🧘',
                        in_array('kun_khmer', $acts) => '🥊',
                        in_array('pool', $acts)      => '🏊',
                        default                      => '🏋️',
                    };
                @endphp
            </div>
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold">{{ $gym->name }}</h1>
                    @if($gym->tier === 'gold')
                        <span class="badge-gold">Gold Partner</span>
                    @elseif($gym->tier === 'silver')
                        <span class="badge-silver">Silver Partner</span>
                    @else
                        <span class="badge-bronze">Bronze Partner</span>
                    @endif
                </div>
                <p class="text-white/50 text-sm mb-4">📍 {{ $gym->address_line1 }}, {{ $gym->district }}, {{ $gym->city }}</p>
                <div class="flex flex-wrap items-center gap-5">
                    <div class="flex items-center gap-1.5 text-sm">
                        <span class="text-amber-400 text-lg">★</span>
                        <span class="font-bold">{{ number_format($gym->average_rating, 1) }}</span>
                        <span class="text-white/40">({{ $gym->review_count }} reviews)</span>
                    </div>
                    @if($gym->isOpenNow())
                        <span class="flex items-center gap-1.5 text-brand-300 text-sm font-medium">
                            <span class="w-2 h-2 rounded-full bg-brand-400 inline-block animate-pulse"></span> Open now
                        </span>
                    @else
                        <span class="flex items-center gap-1.5 text-red-300 text-sm font-medium">
                            <span class="w-2 h-2 rounded-full bg-red-400 inline-block"></span> Closed
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach($gym->activity_types ?? [] as $tag)
                    <span class="bg-white/10 text-white/75 text-xs px-3 py-1 rounded-full border border-white/15">
                        {{ ucwords(str_replace('_', ' ', $tag)) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Content --}}
<div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: main --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- About --}}
        <div class="card p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-3">About</h2>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $gym->description ?? 'No description available.' }}</p>
        </div>

        {{-- Amenities --}}
        @if($gym->amenities)
        <div class="card p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-4">Amenities</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2.5">
                @foreach($gym->amenities as $amenity)
                <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2.5 text-sm text-gray-600 border border-gray-100">
                    <span>{{ match($amenity) {
                        'sauna'       => '🧖',
                        'steam'       => '♨️',
                        'locker'      => '🔒',
                        'parking'     => '🅿️',
                        'towel'       => '🛁',
                        'wifi'        => '📶',
                        'mat_rental'  => '🧘',
                        'ring'        => '🥊',
                        'heavy_bags'  => '👊',
                        'protein_bar' => '☕',
                        default       => '✓',
                    } }}</span>
                    {{ ucwords(str_replace('_', ' ', $amenity)) }}
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Today's classes --}}
        <div class="card p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-4">Today's Classes</h2>
            @forelse($todayClasses as $class)
            <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                <div class="text-brand-600 font-mono text-sm font-bold w-14 shrink-0">
                    {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-gray-800">{{ $class->name }}</div>
                    <div class="text-xs text-gray-400">with {{ $class->trainer_name }} · {{ $class->duration_minutes }} min</div>
                </div>
                <div class="text-xs text-gray-400 shrink-0">{{ $class->max_capacity }} spots</div>
            </div>
            @empty
            <div class="text-center py-8">
                <div class="text-4xl mb-2">📅</div>
                <p class="text-sm text-gray-400">No classes scheduled for today.</p>
            </div>
            @endforelse
        </div>

        {{-- Reviews --}}
        <div class="card p-6">
            <h2 class="font-bold text-gray-900 text-lg mb-4">Reviews</h2>
            @forelse($gym->reviews as $review)
            <div class="py-4 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-2.5 mb-2">
                    <div class="w-8 h-8 rounded-xl bg-brand-500 text-white text-xs flex items-center justify-center font-bold shrink-0">
                        {{ strtoupper(substr($review->user->full_name, 0, 2)) }}
                    </div>
                    <div>
                        <span class="text-sm font-semibold text-gray-800">{{ $review->user->full_name }}</span>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }} text-xs">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
                @if($review->comment)
                <p class="text-sm text-gray-500 ml-11">{{ $review->comment }}</p>
                @endif
            </div>
            @empty
            <div class="text-center py-8">
                <div class="text-4xl mb-2">⭐</div>
                <p class="text-sm text-gray-400">No reviews yet.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Right: sidebar --}}
    <div class="space-y-4">

        {{-- Visit card --}}
        <div class="card p-5">
            <h3 class="font-bold text-gray-900 mb-1">Visit this gym</h3>
            <p class="text-xs text-gray-400 mb-4">Show your membership card at the front desk</p>
            @auth
                <div class="bg-brand-50 border border-brand-200 rounded-xl p-3 text-center">
                    <p class="text-xs text-brand-600 font-medium mb-1">Your membership card</p>
                    @php $employee = auth()->user()->employees()->first(); @endphp
                    @if($employee)
                        <p class="font-mono text-sm text-brand-800 font-bold">{{ $employee->membership_card_no }}</p>
                    @else
                        <p class="text-xs text-gray-400">No active membership</p>
                    @endif
                </div>
            @else
                <a href="{{ route('login') }}" class="btn-primary w-full justify-center">
                    Sign in to check in
                </a>
            @endauth
        </div>

        {{-- Opening hours --}}
        @if($gym->operating_hours)
        <div class="card p-5">
            <h3 class="font-bold text-gray-900 mb-4">Opening Hours</h3>
            <div class="space-y-2">
                @foreach(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'] as $key => $day)
                    @php $hours = $gym->operating_hours[$key] ?? null; @endphp
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ $day }}</span>
                        @if(!$hours || ($hours['closed'] ?? false))
                            <span class="text-red-400 font-medium text-xs">Closed</span>
                        @else
                            <span class="font-semibold text-gray-800 text-xs">{{ $hours['open'] }} – {{ $hours['close'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Contact --}}
        <div class="card p-5">
            <h3 class="font-bold text-gray-900 mb-3">Contact</h3>
            <div class="space-y-2">
                @if($gym->phone)
                <p class="text-sm text-gray-500 flex items-center gap-2"><span>📞</span> {{ $gym->phone }}</p>
                @endif
                @if($gym->email)
                <p class="text-sm text-gray-500 flex items-center gap-2"><span>✉️</span> {{ $gym->email }}</p>
                @endif
                @if($gym->website)
                <a href="{{ $gym->website }}" target="_blank" class="text-sm text-brand-600 hover:text-brand-700 flex items-center gap-2 font-medium">
                    <span>🌐</span> Visit website
                </a>
                @endif
            </div>
        </div>

        {{-- Map --}}
        @if($gym->latitude && $gym->longitude)
        <div class="card overflow-hidden">
            <div class="px-5 pt-5 pb-3">
                <h3 class="font-bold text-gray-900">Location</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $gym->address_line1 }}, {{ $gym->district }}</p>
            </div>
            <div id="gym-detail-map" style="height: 200px;"></div>
        </div>
        @endif
    </div>
</div>

@if($gym->latitude && $gym->longitude)
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.L) return;
    const map = window.L.map('gym-detail-map', { zoomControl: true, scrollWheelZoom: false })
        .setView([{{ $gym->latitude }}, {{ $gym->longitude }}], 15);
    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        maxZoom: 18,
    }).addTo(map);
    window.L.marker([{{ $gym->latitude }}, {{ $gym->longitude }}])
        .addTo(map)
        .bindPopup('<b>{{ addslashes($gym->name) }}</b><br>{{ addslashes($gym->address_line1 . ', ' . $gym->district) }}')
        .openPopup();
});
</script>
@endif

</x-layouts.app>
