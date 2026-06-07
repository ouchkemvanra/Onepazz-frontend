{{-- ============================================================ --}}
{{-- OnePazz — Blade View Templates                             --}}
{{-- Each block shows the file path as a comment above it.      --}}
{{-- Stack: Laravel Blade + Tailwind CSS + Alpine.js            --}}
{{-- ============================================================ --}}


{{-- ─────────────────────────────────────────────────────────── --}}
{{-- resources/views/layouts/app.blade.php                       --}}
{{-- ─────────────────────────────────────────────────────────── --}}
<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OnePazz') — OnePazz</title>

    {{-- Noto Sans Khmer + DM Sans for bilingual support --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 font-sans" x-data="{ currency: '{{ session('currency', 'usd') }}' }">

{{-- Top Navigation --}}
<nav class="sticky top-0 z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-bold text-lg text-teal-600">
                <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
                OnePazz
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center h-full gap-1">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    {{ __('nav.home') }}
                </a>
                <a href="{{ route('gyms.index') }}" class="nav-link {{ request()->routeIs('gyms.*') ? 'active' : '' }}">
                    {{ __('nav.gyms') }}
                </a>
                @auth
                    @if(auth()->user()->isEmployerAdmin())
                        <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                            {{ __('nav.dashboard') }}
                        </a>
                    @endif
                    @if(auth()->user()->isPlatformAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            {{ __('nav.admin') }}
                        </a>
                    @endif
                    <a href="{{ route('profile.index') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                        {{ __('nav.profile') }}
                    </a>
                @endauth
            </div>

            {{-- Right controls --}}
            <div class="flex items-center gap-3">

                {{-- Language toggle --}}
                <div class="flex bg-gray-100 rounded-lg p-1 gap-1 border border-gray-200">
                    <form method="POST" action="{{ route('language.switch', 'en') }}">
                        @csrf
                        <button type="submit" class="lang-btn {{ App::getLocale() === 'en' ? 'active' : '' }}">EN</button>
                    </form>
                    <form method="POST" action="{{ route('language.switch', 'km') }}">
                        @csrf
                        <button type="submit" class="lang-btn khmer {{ App::getLocale() === 'km' ? 'active' : '' }}">ខ្មែរ</button>
                    </form>
                </div>

                {{-- Currency toggle --}}
                <div class="flex bg-gray-100 rounded-lg p-1 gap-1 border border-gray-200">
                    <form method="POST" action="{{ route('currency.switch', 'usd') }}">
                        @csrf
                        <button type="submit" class="cur-btn {{ session('currency', 'usd') === 'usd' ? 'active' : '' }}">$ USD</button>
                    </form>
                    <form method="POST" action="{{ route('currency.switch', 'khr') }}">
                        @csrf
                        <button type="submit" class="cur-btn {{ session('currency') === 'khr' ? 'active' : '' }}">៛ KHR</button>
                    </form>
                </div>

                @auth
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="w-9 h-9 rounded-full bg-teal-600 text-white font-semibold text-sm flex items-center justify-center">
                            {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('profile.index') }}" class="dropdown-item">{{ __('nav.profile') }}</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item w-full text-left text-red-600">{{ __('nav.logout') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary text-sm">{{ __('nav.login') }}</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm">{{ __('nav.register') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Flash messages --}}
@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-6 right-6 z-50 bg-gray-900 text-white px-5 py-3 rounded-xl shadow-xl border-l-4 border-teal-500 text-sm font-medium">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed bottom-6 right-6 z-50 bg-gray-900 text-white px-5 py-3 rounded-xl shadow-xl border-l-4 border-red-500 text-sm font-medium">
        ✗ {{ session('error') }}
    </div>
@endif

{{-- Page content --}}
<main>
    @yield('content')
</main>

</body>
</html>


{{-- ─────────────────────────────────────────────────────────── --}}
{{-- resources/views/gyms/index.blade.php — Gym Directory        --}}
{{-- ─────────────────────────────────────────────────────────── --}}
@extends('layouts.app')
@section('title', __('gyms.title'))

@section('content')
<div class="flex min-h-screen">

    {{-- Filter sidebar --}}
    <aside class="w-64 flex-shrink-0 bg-white border-r border-gray-200 p-6 sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto">
        <form method="GET" action="{{ route('gyms.index') }}" id="filter-form">
            <h3 class="font-semibold text-sm mb-4">{{ __('gyms.filter_title') }}</h3>

            {{-- Search --}}
            <div class="mb-5">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="{{ __('gyms.search_placeholder') }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500"
                    oninput="this.form.submit()">
            </div>

            {{-- Activity type --}}
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('gyms.filter_activity') }}</p>
                @foreach(['weights' => 'Gym & Weights', 'yoga' => 'Yoga', 'swimming' => 'Swimming', 'martial_arts' => 'Martial Arts', 'pilates' => 'Pilates', 'crossfit' => 'CrossFit'] as $val => $label)
                    <label class="flex items-center gap-2 py-1 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="activity[]" value="{{ $val }}"
                            {{ in_array($val, (array)request('activity', [])) ? 'checked' : '' }}
                            class="accent-teal-600" onchange="this.form.submit()">
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            {{-- Tier --}}
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('gyms.filter_tier') }}</p>
                @foreach(['gold' => __('gyms.tier_gold'), 'silver' => __('gyms.tier_silver'), 'bronze' => __('gyms.tier_bronze')] as $val => $label)
                    <label class="flex items-center gap-2 py-1 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="tier[]" value="{{ $val }}"
                            {{ in_array($val, (array)request('tier', [])) ? 'checked' : '' }}
                            class="accent-teal-600" onchange="this.form.submit()">
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            {{-- City --}}
            <div class="mb-5">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('gyms.filter_location') }}</p>
                @foreach(['Phnom Penh', 'Siem Reap', 'Sihanoukville', 'Battambang'] as $city)
                    <label class="flex items-center gap-2 py-1 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="city[]" value="{{ $city }}"
                            {{ in_array($city, (array)request('city', [])) ? 'checked' : '' }}
                            class="accent-teal-600" onchange="this.form.submit()">
                        {{ $city }}
                    </label>
                @endforeach
            </div>
        </form>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 p-7">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-5">
            <p class="text-sm text-gray-500">Showing {{ $gyms->total() }} gyms</p>
            <div class="flex items-center gap-3">
                {{-- Map/List toggle (Alpine) --}}
                <div x-data="{ view: 'list' }" class="flex">
                    <button @click="view = 'list'" :class="view === 'list' ? 'bg-white shadow font-semibold text-gray-800' : 'text-gray-500'"
                        class="px-3 py-1.5 text-sm rounded-l-lg border border-gray-200">☰ List</button>
                    <button @click="view = 'map'; initMap()" :class="view === 'map' ? 'bg-white shadow font-semibold text-gray-800' : 'text-gray-500'"
                        class="px-3 py-1.5 text-sm rounded-r-lg border border-gray-200">🗺 Map</button>

                    {{-- Map container (Leaflet) --}}
                    <div x-show="view === 'map'" id="gym-map" class="absolute inset-0 mt-16 z-10" style="height: 400px;"></div>
                </div>

                <select name="sort" form="filter-form" onchange="document.getElementById('filter-form').submit()"
                    class="border border-gray-200 rounded-lg px-3 py-1.5 text-sm bg-white">
                    <option value="rating"   {{ request('sort') === 'rating'   ? 'selected' : '' }}>Sort: Rating</option>
                    <option value="name"     {{ request('sort') === 'name'     ? 'selected' : '' }}>Sort: Name A–Z</option>
                    <option value="distance" {{ request('sort') === 'distance' ? 'selected' : '' }}>Sort: Distance</option>
                </select>
            </div>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($gyms as $gym)
                <a href="{{ route('gyms.show', $gym->slug) }}"
                   class="flex bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-teal-200 transition-all">
                    <div class="w-24 flex-shrink-0 bg-teal-50 flex items-center justify-center text-4xl">
                        {{ match(true) {
                            in_array('yoga', $gym->activity_types ?? []) => '🧘',
                            in_array('muay_thai', $gym->activity_types ?? []) => '🥊',
                            in_array('swimming', $gym->activity_types ?? []) => '🏊',
                            default => '🏋️'
                        } }}
                    </div>
                    <div class="p-4 flex-1">
                        <div class="flex items-start justify-between mb-1">
                            <h3 class="font-semibold text-sm">{{ $gym->name }}</h3>
                            <span class="badge-{{ $gym->tier }} text-xs px-2 py-0.5 rounded-full">{{ ucfirst($gym->tier) }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mb-2">📍 {{ $gym->district }}, {{ $gym->city }}</p>
                        <div class="flex flex-wrap gap-1 mb-2">
                            @foreach(array_slice($gym->activity_types ?? [], 0, 3) as $tag)
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">{{ ucwords(str_replace('_', ' ', $tag)) }}</span>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-1 text-xs text-gray-500">
                            <span class="text-amber-400">★</span>
                            {{ number_format($gym->average_rating, 1) }}
                            ({{ $gym->review_count }} {{ __('gyms.reviews_count', ['count' => '']) }})
                            @if($gym->isOpenNow())
                                <span class="ml-auto text-teal-600 font-medium">● Open now</span>
                            @else
                                <span class="ml-auto text-red-500 font-medium">● Closed</span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-2 text-center py-16 text-gray-400">
                    <div class="text-4xl mb-3">🏋️</div>
                    <p>No gyms found matching your filters.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">{{ $gyms->links() }}</div>
    </div>
</div>

{{-- Leaflet map (loaded lazily) --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const gymData = @json($gyms->items()->map(fn($g) => [
    'name' => $g->name, 'slug' => $g->slug,
    'lat'  => $g->latitude, 'lng' => $g->longitude,
    'tier' => $g->tier, 'rating' => $g->average_rating,
]));

let mapInited = false;
function initMap() {
    if (mapInited || !gymData.some(g => g.lat)) return;
    mapInited = true;
    const map = L.map('gym-map').setView([11.5564, 104.9282], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    gymData.forEach(g => {
        if (!g.lat) return;
        L.marker([g.lat, g.lng])
            .addTo(map)
            .bindPopup(`<b>${g.name}</b><br>⭐ ${g.rating}<br><a href="/gyms/${g.slug}">View →</a>`);
    });
}
</script>
@endsection


{{-- ─────────────────────────────────────────────────────────── --}}
{{-- resources/views/dashboard/index.blade.php — Employer Dash   --}}
{{-- ─────────────────────────────────────────────────────────── --}}
@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section('content')
<div class="p-8">
    {{-- Header --}}
    <div class="flex items-start justify-between mb-7">
        <div>
            <h1 class="text-2xl font-bold tracking-tight">
                {{ now()->hour < 12 ? __('dashboard.greeting_morning', ['name' => auth()->user()->full_name]) : __('dashboard.greeting_afternoon', ['name' => auth()->user()->full_name]) }}
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ $employer->company_name }} — {{ $sub?->plan?->name }} Plan · {{ $activeEmployees }} employees
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dashboard.reports.csv', ['month' => now()->format('Y-m')]) }}"
               class="btn-secondary text-sm">📥 Export CSV</a>
            <a href="{{ route('dashboard.reports.pdf', ['month' => now()->format('Y-m')]) }}"
               class="btn-primary text-sm">📄 Export PDF</a>
        </div>
    </div>

    {{-- KPI metrics --}}
    <div class="grid grid-cols-4 gap-4 mb-7">
        <div class="metric-card">
            <p class="metric-label">{{ __('dashboard.metric_active_employees') }}</p>
            <p class="metric-value text-teal-600">{{ $activeEmployees }}</p>
            <p class="text-xs text-green-600">↑ active this month</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">Check-ins ({{ now()->format('M') }})</p>
            <p class="metric-value">{{ number_format($checkinsThisMonth) }}</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">{{ __('dashboard.metric_monthly_cost') }}</p>
            <p class="metric-value">{{ format_currency($monthlyCostUsd) }}</p>
            <p class="text-xs text-gray-400">{{ $activeEmployees }} × {{ format_currency($sub?->plan?->price_usd ?? 0) }}/mo</p>
        </div>
        <div class="metric-card">
            <p class="metric-label">{{ __('dashboard.metric_utilisation') }}</p>
            <p class="metric-value text-orange-500">{{ $utilisationRate }}%</p>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="grid grid-cols-3 gap-5 mb-5">
        {{-- Weekly bar chart --}}
        <div class="col-span-2 card">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-semibold">{{ __('dashboard.chart_weekly_checkins') }}</h3>
                <a href="{{ route('dashboard.reports.index') }}" class="text-sm text-teal-600">View all →</a>
            </div>
            <div class="flex items-end gap-2 h-36">
                @php
                    $days = collect(); $max = 1;
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i)->format('Y-m-d');
                        $count = $weeklyCheckins[$date] ?? 0;
                        $days->push(['label' => now()->subDays($i)->format('D'), 'count' => $count, 'today' => $i === 0]);
                        if ($count > $max) $max = $count;
                    }
                @endphp
                @foreach($days as $day)
                    <div class="flex flex-col items-center flex-1 h-full justify-end gap-1">
                        <span class="text-xs text-gray-400">{{ $day['count'] }}</span>
                        <div class="w-full rounded-t border-2
                            {{ $day['today'] ? 'bg-teal-500 border-teal-600' : 'bg-teal-100 border-teal-200' }}"
                            style="height: {{ $max > 0 ? round($day['count'] / $max * 100) : 0 }}%"
                            title="{{ $day['label'] }}: {{ $day['count'] }} check-ins">
                        </div>
                        <span class="text-xs text-gray-400">{{ $day['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top gyms --}}
        <div class="card">
            <h3 class="font-semibold mb-4">{{ __('dashboard.top_gyms') }}</h3>
            @foreach($topGyms as $g)
                <div class="mb-3">
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium truncate">{{ $g->gym?->name }}</span>
                        <span class="text-gray-500 ml-2">{{ $g->visits }}</span>
                    </div>
                    <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-teal-500 rounded-full"
                            style="width: {{ $topGyms->first()->visits > 0 ? round($g->visits / $topGyms->first()->visits * 100) : 0 }}%">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Employee table --}}
    <div class="card">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-semibold">{{ __('dashboard.recent_activity') }}</h3>
            <div class="flex gap-2">
                <a href="{{ route('dashboard.employees.invite') }}" class="btn-secondary text-sm">
                    {{ __('dashboard.invite_employee') }}
                </a>
                <a href="{{ route('dashboard.employees.index') }}" class="text-sm text-teal-600">
                    {{ __('dashboard.view_all') }}
                </a>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="th">{{ __('dashboard.col_employee') }}</th>
                    <th class="th">{{ __('dashboard.col_department') }}</th>
                    <th class="th">{{ __('dashboard.col_plan') }}</th>
                    <th class="th">{{ __('dashboard.col_last_checkin') }}</th>
                    <th class="th">{{ __('dashboard.col_visits') }}</th>
                    <th class="th">{{ __('dashboard.col_status') }}</th>
                    <th class="th">{{ __('dashboard.col_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentEmployees as $employee)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="td">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-teal-500 text-white text-xs flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($employee->user->full_name, 0, 2)) }}
                            </div>
                            <span class="font-medium">{{ $employee->user->full_name }}</span>
                        </div>
                    </td>
                    <td class="td text-gray-500">{{ $employee->department ?? '—' }}</td>
                    <td class="td"><span class="badge-teal">{{ $employee->subscription?->plan?->name }}</span></td>
                    <td class="td font-mono text-xs text-gray-500">
                        {{ $employee->checkins()->latest('checked_in_at')->value('checked_in_at')
                            ? format_date_kh($employee->checkins()->latest('checked_in_at')->value('checked_in_at'))
                            : 'Never' }}
                    </td>
                    <td class="td font-semibold">{{ $employee->checkins_count }}</td>
                    <td class="td">
                        @if($employee->status === 'active')
                            <span class="badge-green">{{ __('dashboard.status_active') }}</span>
                        @elseif($employee->status === 'suspended')
                            <span class="badge-red">{{ __('dashboard.status_suspended') }}</span>
                        @else
                            <span class="badge-gray">{{ __('dashboard.status_inactive') }}</span>
                        @endif
                    </td>
                    <td class="td">
                        <div class="flex gap-2">
                            <a href="{{ route('dashboard.employees.index') }}" class="btn-xs">Edit</a>
                            @if($employee->status === 'active')
                                <form method="POST" action="{{ route('dashboard.employees.suspend', $employee) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-xs text-red-600"
                                        onclick="return confirm('Suspend {{ $employee->user->full_name }}?')">
                                        Suspend
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('dashboard.employees.restore', $employee) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-xs text-green-600">Restore</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


{{-- ─────────────────────────────────────────────────────────── --}}
{{-- resources/views/dashboard/billing/index.blade.php           --}}
{{-- ─────────────────────────────────────────────────────────── --}}
@extends('layouts.dashboard')
@section('title', __('billing.title'))

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-7">{{ __('billing.title') }}</h1>

    {{-- Bank transfer card --}}
    <div class="bg-gradient-to-br from-gray-900 to-blue-950 rounded-xl p-6 text-white mb-6 max-w-lg">
        <p class="text-xs text-white/50 mb-1">{{ $bankDetails['bank'] }} · Corporate Account</p>
        <p class="text-xl font-mono font-semibold tracking-widest mb-1">{{ $bankDetails['account'] }}</p>
        <p class="text-sm text-white/70 mb-4">{{ $bankDetails['holder'] }}</p>
        <div class="bg-white/10 rounded-lg p-3 text-xs text-white/60 leading-relaxed">
            ⚠️ {{ __('billing.reference_instruction') }}<br>
            {{ __('billing.confirmation_note') }}<br>
            {{ __('billing.contact_billing') }}
        </div>
    </div>

    {{-- Invoice list --}}
    <div class="card">
        <h3 class="font-semibold mb-5">Invoice History</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="th">{{ __('billing.invoice_number') }}</th>
                    <th class="th">{{ __('billing.billing_period') }}</th>
                    <th class="th">{{ __('billing.amount_usd') }}</th>
                    <th class="th">{{ __('billing.amount_khr') }}</th>
                    <th class="th">Status</th>
                    <th class="th">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="td font-mono text-xs">{{ $invoice->invoice_number }}</td>
                    <td class="td text-gray-500">
                        {{ $invoice->billing_period_start->format('M Y') }}
                    </td>
                    <td class="td font-semibold">${{ number_format($invoice->total_usd, 2) }}</td>
                    <td class="td text-gray-500">{{ number_format($invoice->total_khr) }} ៛</td>
                    <td class="td">
                        @switch($invoice->status)
                            @case('paid')    <span class="badge-green">Confirmed</span> @break
                            @case('pending_verification') <span class="badge-amber">Pending</span> @break
                            @case('overdue') <span class="badge-red">Overdue</span> @break
                            @default         <span class="badge-gray">Unpaid</span>
                        @endswitch
                    </td>
                    <td class="td">
                        @if(in_array($invoice->status, ['unpaid', 'overdue']))
                            <a href="{{ route('dashboard.billing.pay', $invoice) }}" class="btn-xs btn-primary">
                                Submit Payment
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">{{ $invoices->links() }}</div>
    </div>
</div>
@endsection


{{-- ─────────────────────────────────────────────────────────── --}}
{{-- resources/views/admin/payments/index.blade.php              --}}
{{-- ─────────────────────────────────────────────────────────── --}}
@extends('layouts.admin')
@section('title', 'Payments')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-center mb-7">
        <div>
            <h1 class="text-2xl font-bold">Pending Payment Confirmations</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $pending->count() }} transfers awaiting review</p>
        </div>
    </div>

    <div class="card mb-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="th">Invoice</th>
                    <th class="th">Employer</th>
                    <th class="th">Amount (USD)</th>
                    <th class="th">Amount (KHR)</th>
                    <th class="th">Transfer Date</th>
                    <th class="th">Reference</th>
                    <th class="th">Receipt</th>
                    <th class="th">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $payment)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="td font-mono text-xs">{{ $payment->invoice->invoice_number }}</td>
                    <td class="td"><span class="font-semibold">{{ $payment->employer->company_name }}</span></td>
                    <td class="td font-semibold">${{ number_format($payment->amount_usd, 2) }}</td>
                    <td class="td text-gray-500">{{ number_format($payment->amount_khr) }} ៛</td>
                    <td class="td font-mono text-xs">{{ format_date_kh($payment->transfer_date) }}</td>
                    <td class="td font-mono text-xs text-gray-500">{{ $payment->transfer_reference }}</td>
                    <td class="td">
                        @if($payment->receipt_path)
                            <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank"
                               class="text-teal-600 text-xs underline">View</a>
                        @else
                            <span class="text-gray-400 text-xs">None</span>
                        @endif
                    </td>
                    <td class="td">
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-confirm">Confirm</button>
                            </form>

                            <button onclick="document.getElementById('reject-{{ $payment->id }}').classList.toggle('hidden')"
                                class="btn-reject-outline">Reject</button>
                        </div>
                        {{-- Rejection reason form (hidden by default) --}}
                        <form id="reject-{{ $payment->id }}" method="POST"
                            action="{{ route('admin.payments.reject', $payment) }}"
                            class="hidden mt-2">
                            @csrf @method('PATCH')
                            <input type="text" name="reason" placeholder="Reason for rejection..."
                                class="border border-gray-200 rounded px-2 py-1 text-xs w-full mb-1" required>
                            <button type="submit" class="btn-reject text-xs">Confirm Rejection</button>
                        </form>
                    </td>
                </tr>
                @empty
                    <tr><td colspan="8" class="td text-center text-gray-400 py-8">No pending payments 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
