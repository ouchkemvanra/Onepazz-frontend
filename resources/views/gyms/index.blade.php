<x-layouts.app title="{{ __('nav.gyms') }}">

<div class="flex" style="min-height: calc(100vh - 4rem);">

    {{-- Filter sidebar --}}
    <aside class="hidden lg:block w-56 flex-shrink-0 bg-white border-r border-gray-100 sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto">
        <form method="GET" action="{{ route('gyms.index') }}" id="filter-form" class="p-4 space-y-5">

            <div>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('gym.search_placeholder') }}"
                    class="input text-sm"
                    onchange="this.form.submit()">
            </div>

            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tier</p>
                @foreach(['gold' => 'Gold', 'silver' => 'Silver', 'bronze' => 'Bronze'] as $val => $label)
                <label class="flex items-center gap-2 py-1.5 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                    <input type="checkbox" name="tier[]" value="{{ $val }}"
                        {{ in_array($val, (array) request('tier', [])) ? 'checked' : '' }}
                        class="accent-brand-500 w-3.5 h-3.5" onchange="this.form.submit()">
                    {{ $label }}
                </label>
                @endforeach
            </div>

            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Activity</p>
                @foreach(['weights' => 'Weights', 'yoga' => 'Yoga', 'pool' => 'Swimming', 'kun_khmer' => 'Kun Khmer', 'cardio' => 'Cardio', 'crossfit' => 'CrossFit'] as $val => $label)
                <label class="flex items-center gap-2 py-1.5 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                    <input type="checkbox" name="activity[]" value="{{ $val }}"
                        {{ in_array($val, (array) request('activity', [])) ? 'checked' : '' }}
                        class="accent-brand-500 w-3.5 h-3.5" onchange="this.form.submit()">
                    {{ $label }}
                </label>
                @endforeach
            </div>

            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">City</p>
                @foreach(['Phnom Penh', 'Siem Reap', 'Sihanoukville', 'Battambang'] as $city)
                <label class="flex items-center gap-2 py-1.5 text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                    <input type="checkbox" name="city[]" value="{{ $city }}"
                        {{ in_array($city, (array) request('city', [])) ? 'checked' : '' }}
                        class="accent-brand-500 w-3.5 h-3.5" onchange="this.form.submit()">
                    {{ $city }}
                </label>
                @endforeach
            </div>

            @if(request()->anyFilled(['search', 'tier', 'activity', 'city']))
            <a href="{{ route('gyms.index') }}" class="text-xs text-red-400 hover:text-red-600 font-medium flex items-center gap-1">
                ✕ Clear filters
            </a>
            @endif
        </form>
    </aside>

    {{-- Main --}}
    <div class="flex-1 p-6" x-data="{ view: 'list' }">

        {{-- Header bar --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-gray-900">Gym Directory</h1>
                <p class="text-sm text-gray-400 mt-0.5">{{ $gyms->total() }} gym{{ $gyms->total() !== 1 ? 's' : '' }} found</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center bg-gray-100 rounded-xl p-0.5">
                    <button type="button" @click="view = 'list'"
                        :class="view === 'list' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all">
                        ☰ List
                    </button>
                    <button type="button" @click="view = 'map'; $nextTick(() => initMap())"
                        :class="view === 'map' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all">
                        🗺 Map
                    </button>
                </div>
                <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="input text-sm py-1.5">
                    <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating</option>
                    <option value="name"   {{ request('sort') === 'name'   ? 'selected' : '' }}>Name A–Z</option>
                    <option value="tier"   {{ request('sort') === 'tier'   ? 'selected' : '' }}>Tier</option>
                </select>
            </div>
        </div>

        {{-- Map view --}}
        <div x-show="view === 'map'" x-cloak>
            <div id="gym-map" class="rounded-2xl overflow-hidden border border-gray-200 shadow-card" style="height: 600px;"></div>
            @if($mapGyms->isEmpty())
            <p class="text-sm text-gray-400 text-center mt-4">No gyms with location data yet.</p>
            @endif
        </div>

        {{-- List view --}}
        <div x-show="view === 'list'">
            @if($gyms->isEmpty())
            <div class="text-center py-20">
                <div class="text-6xl mb-4">🏋️</div>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No gyms found</h3>
                <p class="text-gray-400 text-sm mb-4">Try adjusting your filters or search term.</p>
                <a href="{{ route('gyms.index') }}" class="btn-secondary">Clear filters</a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($gyms as $gym)
                <a href="{{ route('gyms.show', $gym->slug) }}" class="card card-hover overflow-hidden group flex flex-col">
                    <div class="h-36 bg-gradient-to-br from-brand-50 to-brand-100 flex items-center justify-center text-5xl relative">
                        @php
                            $acts = $gym->activity_types ?? [];
                            echo match(true) {
                                in_array('yoga', $acts)      => '🧘',
                                in_array('kun_khmer', $acts) => '🥊',
                                in_array('pool', $acts)      => '🏊',
                                in_array('crossfit', $acts)  => '💪',
                                default                      => '🏋️',
                            };
                        @endphp
                        <div class="absolute top-3 right-3">
                            @if($gym->tier === 'gold')
                                <span class="badge-gold">Gold</span>
                            @elseif($gym->tier === 'silver')
                                <span class="badge-silver">Silver</span>
                            @else
                                <span class="badge-bronze">Bronze</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-4 flex-1">
                        <h3 class="font-bold text-gray-900 mb-1 group-hover:text-brand-600 transition-colors">{{ $gym->name }}</h3>
                        <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                            <span>📍</span> {{ $gym->district }}, {{ $gym->city }}
                        </p>
                        <div class="flex flex-wrap gap-1.5 mb-3">
                            @foreach(array_slice($gym->activity_types ?? [], 0, 3) as $tag)
                            <span class="badge badge-gray text-xs">{{ ucwords(str_replace('_', ' ', $tag)) }}</span>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex items-center gap-1 text-xs text-gray-400">
                                <span class="text-amber-400 text-sm">★</span>
                                <span class="font-semibold text-gray-700">{{ number_format($gym->average_rating, 1) }}</span>
                                <span>({{ $gym->review_count }})</span>
                            </div>
                            @if($gym->isOpenNow())
                                <span class="text-xs text-brand-500 font-medium flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-brand-500 inline-block"></span> Open</span>
                            @else
                                <span class="text-xs text-red-400 font-medium flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span> Closed</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>

            @if($gyms->hasPages())
            <div class="mt-8">{{ $gyms->links() }}</div>
            @endif
            @endif
        </div>
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
    if (mapInited || !window.L) return;
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
        const color = tierColors[g.tier] || '#0EA88A';
        const icon = window.L.divIcon({
            className: '',
            html: '<div style="width:28px;height:28px;border-radius:50%;background:' + color + ';border:2px solid white;box-shadow:0 2px 6px rgba(0,0,0,.3);display:flex;align-items:center;justify-content:center;font-size:12px;">🏋</div>',
            iconSize: [28, 28], iconAnchor: [14, 14], popupAnchor: [0, -16],
        });
        const rating = g.rating ? parseFloat(g.rating).toFixed(1) : '—';
        window.L.marker([g.lat, g.lng], { icon })
            .addTo(map)
            .bindPopup(
                '<div style="min-width:160px"><p style="font-weight:600;margin:0 0 2px">' + g.name + '</p>' +
                '<p style="color:#6b7280;font-size:11px;margin:0 0 6px">⭐ ' + rating + ' · ' + g.tier + '</p>' +
                '<a href="/gyms/' + g.slug + '" style="color:#0EA88A;font-size:12px;font-weight:500">View details →</a></div>',
                { maxWidth: 220 }
            );
    });
}
</script>

</x-layouts.app>
