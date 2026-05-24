<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Directory — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit
        </a>
        <div class="flex items-center gap-4">
            <a href="/gyms" class="text-sm font-medium text-teal-600">{{ __('nav.gyms') }}</a>
            @auth
                <a href="/dashboard" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.dashboard') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.logout') }}</button>
                </form>
            @else
                <a href="/login" class="text-sm bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">{{ __('auth.sign_in') }}</a>
            @endauth
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs {{ App::getLocale() === 'en' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">EN</button>
                </form>
                <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline border-l border-gray-200">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs font-khmer {{ App::getLocale() === 'km' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">ខ្មែរ</button>
                </form>
            </div>
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('currency.switch', 'usd') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs {{ $currency === 'usd' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">$ USD</button>
                </form>
                <form method="POST" action="{{ route('currency.switch', 'khr') }}" class="inline border-l border-gray-200">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs {{ $currency === 'khr' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">៛ KHR</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<div class="flex min-h-screen">

    {{-- FILTER SIDEBAR --}}
    <aside class="w-56 flex-shrink-0 bg-white border-r border-gray-200 p-5 sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto">
        <form method="GET" action="{{ route('gyms.index') }}" id="filter-form">

            {{-- Search --}}
            <div class="mb-5">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('gym.search_placeholder') }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    onchange="this.form.submit()">
            </div>

            {{-- Tier --}}
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Tier</p>
                @foreach(['gold' => 'Gold', 'silver' => 'Silver', 'bronze' => 'Bronze'] as $val => $label)
                <label class="flex items-center gap-2 py-1 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="tier[]" value="{{ $val }}"
                        {{ in_array($val, (array) request('tier', [])) ? 'checked' : '' }}
                        class="accent-teal-600" onchange="this.form.submit()">
                    {{ $label }}
                </label>
                @endforeach
            </div>

            {{-- Activity --}}
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Activity</p>
                @foreach(['weights' => 'Weights', 'yoga' => 'Yoga', 'pool' => 'Swimming', 'kun_khmer' => 'Kun Khmer', 'cardio' => 'Cardio', 'crossfit' => 'CrossFit'] as $val => $label)
                <label class="flex items-center gap-2 py-1 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="activity[]" value="{{ $val }}"
                        {{ in_array($val, (array) request('activity', [])) ? 'checked' : '' }}
                        class="accent-teal-600" onchange="this.form.submit()">
                    {{ $label }}
                </label>
                @endforeach
            </div>

            {{-- City --}}
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">City</p>
                @foreach(['Phnom Penh', 'Siem Reap', 'Sihanoukville', 'Battambang'] as $city)
                <label class="flex items-center gap-2 py-1 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="city[]" value="{{ $city }}"
                        {{ in_array($city, (array) request('city', [])) ? 'checked' : '' }}
                        class="accent-teal-600" onchange="this.form.submit()">
                    {{ $city }}
                </label>
                @endforeach
            </div>

            {{-- Clear --}}
            @if(request()->anyFilled(['search', 'tier', 'activity', 'city']))
            <a href="{{ route('gyms.index') }}" class="text-xs text-red-400 hover:underline">Clear filters</a>
            @endif

        </form>
    </aside>

    {{-- MAIN --}}
    <div class="flex-1 p-6" x-data="{ view: 'list' }">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <p class="text-sm text-gray-500">Showing {{ $gyms->total() }} gym{{ $gyms->total() !== 1 ? 's' : '' }}</p>
            <div class="flex items-center gap-2">
                {{-- List / Map toggle --}}
                <div class="flex rounded-lg overflow-hidden border border-gray-200">
                    <button type="button" @click="view = 'list'"
                        :class="view === 'list' ? 'bg-teal-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm transition">☰ List</button>
                    <button type="button" @click="view = 'map'; $nextTick(() => initMap())"
                        :class="view === 'map' ? 'bg-teal-600 text-white' : 'bg-white text-gray-500 hover:bg-gray-50'"
                        class="px-3 py-1.5 text-sm border-l border-gray-200 transition">🗺 Map</button>
                </div>
                <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white focus:outline-none">
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Sort: Rating</option>
                    <option value="name"   {{ request('sort') === 'name'   ? 'selected' : '' }}>Sort: Name A–Z</option>
                    <option value="tier"   {{ request('sort') === 'tier'   ? 'selected' : '' }}>Sort: Tier</option>
                </select>
            </div>
        </div>

        {{-- Map view --}}
        <div x-show="view === 'map'" x-cloak>
            <div id="gym-map" class="rounded-xl overflow-hidden border border-gray-200" style="height: 600px;"></div>
            @if($mapGyms->isEmpty())
            <p class="text-sm text-gray-400 text-center mt-4">No gyms with location data yet.</p>
            @endif
        </div>

        {{-- Grid --}}
        <div x-show="view === 'list'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @forelse($gyms as $gym)
            <a href="{{ route('gyms.show', $gym->slug) }}"
                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-teal-200 transition block">

                {{-- Image --}}
                <div class="h-36 bg-teal-50 flex items-center justify-center text-5xl relative">
                    @php
                        $activities = $gym->activity_types ?? [];
                        $icon = match(true) {
                            in_array('yoga', $activities)      => '🧘',
                            in_array('kun_khmer', $activities) => '🥊',
                            in_array('pool', $activities)      => '🏊',
                            in_array('crossfit', $activities)  => '💪',
                            default                            => '🏋️',
                        };
                    @endphp
                    {{ $icon }}
                    <span class="absolute top-3 right-3 text-xs font-semibold px-2 py-0.5 rounded-full
                        {{ $gym->tier === 'gold' ? 'bg-amber-100 text-amber-800' : ($gym->tier === 'silver' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                        {{ ucfirst($gym->tier) }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="p-4">
                    <h3 class="font-semibold text-sm mb-1">{{ $gym->name }}</h3>
                    <p class="text-xs text-gray-400 mb-3">📍 {{ $gym->district }}, {{ $gym->city }}</p>

                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach(array_slice($gym->activity_types ?? [], 0, 3) as $tag)
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">
                            {{ ucwords(str_replace('_', ' ', $tag)) }}
                        </span>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-400 flex items-center gap-1">
                            <span class="text-amber-400">★</span>
                            {{ number_format($gym->average_rating, 1) }}
                            ({{ $gym->review_count }})
                        </div>
                        @if($gym->isOpenNow())
                            <span class="text-xs text-teal-600 font-medium">● Open now</span>
                        @else
                            <span class="text-xs text-red-400 font-medium">● Closed</span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-20 text-gray-400">
                <div class="text-4xl mb-3">🏋️</div>
                <p class="text-sm">No gyms found matching your filters.</p>
                <a href="{{ route('gyms.index') }}" class="text-teal-600 text-sm mt-2 inline-block hover:underline">Clear filters</a>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($gyms->hasPages())
        <div class="mt-6">{{ $gyms->links() }}</div>
        @endif

        </div>{{-- end list view --}}

    </div>
</div>

@php
$gymMapData = $mapGyms->map(fn($g) => [
    'name'   => $g->name,
    'slug'   => $g->slug,
    'lat'    => (float) $g->latitude,
    'lng'    => (float) $g->longitude,
    'tier'   => $g->tier,
    'rating' => $g->average_rating,
])->values()->all();
@endphp
<script>
const gymData = {!! json_encode($gymMapData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) !!};

let mapInited = false;
function initMap() {
    if (mapInited) return;
    if (!gymData.some(g => g.lat)) return;
    mapInited = true;

    const map = window.L.map('gym-map').setView([11.5564, 104.9282], 12);

    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(map);

    const tierColors = { gold: '#d97706', silver: '#6b7280', bronze: '#c2410c' };

    gymData.forEach(function(g) {
        if (!g.lat || !g.lng) return;

        const color = tierColors[g.tier] || '#0f766e';
        const icon  = window.L.divIcon({
            className: '',
            html: '<div style="width:28px;height:28px;border-radius:50%;background:' + color + ';border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,.35);display:flex;align-items:center;justify-content:center;color:white;font-size:12px;font-weight:700;">🏋</div>',
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            popupAnchor: [0, -16],
        });

        const rating = g.rating ? parseFloat(g.rating).toFixed(1) : '—';
        const tier   = g.tier.charAt(0).toUpperCase() + g.tier.slice(1);

        window.L.marker([g.lat, g.lng], { icon: icon })
            .addTo(map)
            .bindPopup(
                '<div style="min-width:160px;font-family:inherit">' +
                '<p style="font-weight:600;margin:0 0 2px">' + g.name + '</p>' +
                '<p style="color:#6b7280;font-size:11px;margin:0 0 6px">⭐ ' + rating + ' · ' + tier + '</p>' +
                '<a href="/gyms/' + g.slug + '" style="color:#0f766e;font-size:12px;font-weight:500;">View details →</a>' +
                '</div>',
                { maxWidth: 220 }
            );
    });
}
</script>

</body>
</html>