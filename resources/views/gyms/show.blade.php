<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $gym->name }} — OnePazz</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            OnePazz
        </a>
        <div class="flex items-center gap-4">
            <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('gym.back_to_gyms') }}</a>
            @auth
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

{{-- HERO --}}
<div class="bg-gradient-to-br from-gray-900 to-gray-800 text-white">
    <div class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex gap-6 items-start">

            {{-- Icon --}}
            <div class="w-20 h-20 rounded-xl bg-teal-600/20 border border-teal-500/30 flex items-center justify-center text-4xl flex-shrink-0">
                @php
                    $activities = $gym->activity_types ?? [];
                    echo match(true) {
                        in_array('yoga', $activities)      => '🧘',
                        in_array('kun_khmer', $activities) => '🥊',
                        in_array('pool', $activities)      => '🏊',
                        default                            => '🏋️',
                    };
                @endphp
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-3xl font-bold">{{ $gym->name }}</h1>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $gym->tier === 'gold' ? 'bg-amber-400/20 text-amber-300' : ($gym->tier === 'silver' ? 'bg-gray-400/20 text-gray-300' : 'bg-orange-400/20 text-orange-300') }}">
                        {{ ucfirst($gym->tier) }} Partner
                    </span>
                </div>
                <p class="text-gray-400 text-sm mb-3">📍 {{ $gym->address_line1 }}, {{ $gym->district }}, {{ $gym->city }}</p>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 text-sm">
                        <span class="text-amber-400 text-lg">★</span>
                        <span class="font-semibold">{{ number_format($gym->average_rating, 1) }}</span>
                        <span class="text-gray-400">({{ $gym->review_count }} reviews)</span>
                    </div>
                    @if($gym->isOpenNow())
                        <span class="text-teal-400 text-sm font-medium">● Open now</span>
                    @else
                        <span class="text-red-400 text-sm font-medium">● Closed</span>
                    @endif
                </div>

                {{-- Tags --}}
                <div class="flex flex-wrap gap-2 mt-4">
                    @foreach($gym->activity_types ?? [] as $tag)
                    <span class="bg-white/10 text-white/80 text-xs px-3 py-1 rounded-full">
                        {{ ucwords(str_replace('_', ' ', $tag)) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- CONTENT --}}
<div class="max-w-6xl mx-auto px-6 py-8 grid grid-cols-3 gap-6">

    {{-- LEFT: Main content --}}
    <div class="col-span-2 space-y-6">

        {{-- About --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-lg mb-3">About</h2>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $gym->description ?? 'No description available.' }}</p>
        </div>

        {{-- Amenities --}}
        @if($gym->amenities)
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-lg mb-4">Amenities</h2>
            <div class="grid grid-cols-3 gap-3">
                @foreach($gym->amenities as $amenity)
                <div class="flex items-center gap-2 bg-gray-50 rounded-lg px-3 py-2.5 text-sm text-gray-600">
                    @php echo match($amenity) {
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
                    }; @endphp
                    {{ ucwords(str_replace('_', ' ', $amenity)) }}
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Today's Classes --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-lg mb-4">Today's Classes</h2>
            @forelse($todayClasses as $class)
            <div class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0">
                <div class="text-teal-600 font-mono text-sm font-semibold w-14">
                    {{ \Carbon\Carbon::parse($class->start_time)->format('H:i') }}
                </div>
                <div class="flex-1">
                    <div class="font-medium text-sm">{{ $class->name }}</div>
                    <div class="text-xs text-gray-400">with {{ $class->trainer_name }} · {{ $class->duration_minutes }} min</div>
                </div>
                <div class="text-xs text-gray-400">{{ $class->max_capacity }} spots</div>
            </div>
            @empty
            <p class="text-sm text-gray-400">No classes scheduled for today.</p>
            @endforelse
        </div>

        {{-- Reviews --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="font-semibold text-lg mb-4">Reviews</h2>
            @forelse($gym->reviews as $review)
            <div class="py-3 border-b border-gray-100 last:border-0">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-7 h-7 rounded-full bg-teal-500 text-white text-xs flex items-center justify-center font-semibold">
                        {{ strtoupper(substr($review->user->full_name, 0, 2)) }}
                    </div>
                    <span class="text-sm font-medium">{{ $review->user->full_name }}</span>
                    <span class="text-amber-400 text-xs">{{ str_repeat('★', $review->rating) }}</span>
                </div>
                <p class="text-sm text-gray-500 ml-9">{{ $review->comment }}</p>
            </div>
            @empty
            <p class="text-sm text-gray-400">No reviews yet.</p>
            @endforelse
        </div>

    </div>

    {{-- RIGHT: Sidebar --}}
    <div class="space-y-4">

        {{-- Check in card --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold mb-1">Visit this gym</h3>
            <p class="text-xs text-gray-400 mb-4">Show your membership card at the front desk</p>

            @auth
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 mb-4 text-center">
                    <p class="text-xs text-teal-600 font-medium mb-1">Your membership</p>
                    @php $employee = auth()->user()->employees()->first(); @endphp
                    @if($employee)
                        <p class="font-mono text-xs text-teal-800 font-semibold">{{ $employee->membership_card_no }}</p>
                    @else
                        <p class="text-xs text-gray-400">No active membership</p>
                    @endif
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="block w-full text-center bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-lg text-sm mb-4 transition">
                    Sign in to check in
                </a>
            @endauth
        </div>

        {{-- Opening hours --}}
        @if($gym->operating_hours)
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold mb-4">Opening Hours</h3>
            <div class="space-y-2">
                @foreach(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'] as $key => $day)
                    @php $hours = $gym->operating_hours[$key] ?? null; @endphp
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ $day }}</span>
                        @if(!$hours || ($hours['closed'] ?? false))
                            <span class="text-red-400 font-medium">Closed</span>
                        @else
                            <span class="font-medium">{{ $hours['open'] }} – {{ $hours['close'] }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Contact --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="font-semibold mb-3">Contact</h3>
            @if($gym->phone)
            <p class="text-sm text-gray-500 mb-1">📞 {{ $gym->phone }}</p>
            @endif
            @if($gym->email)
            <p class="text-sm text-gray-500 mb-1">✉️ {{ $gym->email }}</p>
            @endif
            @if($gym->website)
            <a href="{{ $gym->website }}" target="_blank" class="text-sm text-teal-600 hover:underline">🌐 Visit website</a>
            @endif
        </div>

        {{-- Location map --}}
        @if($gym->latitude && $gym->longitude)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 pt-5 pb-3">
                <h3 class="font-semibold">Location</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $gym->address_line1 }}, {{ $gym->district }}</p>
            </div>
            <div id="gym-detail-map" style="height: 220px;"></div>
        </div>
        @endif

    </div>
</div>

@if($gym->latitude && $gym->longitude)
<script>
document.addEventListener('DOMContentLoaded', function () {
    const map = window.L.map('gym-detail-map', { zoomControl: true, scrollWheelZoom: false })
        .setView([{{ $gym->latitude }}, {{ $gym->longitude }}], 15);

    window.L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 18,
    }).addTo(map);

    window.L.marker([{{ $gym->latitude }}, {{ $gym->longitude }}])
        .addTo(map)
        .bindPopup('<b>{{ addslashes($gym->name) }}</b><br>{{ addslashes($gym->address_line1 . ', ' . $gym->district) }}')
        .openPopup();
});
</script>
@endif

</body>
</html>