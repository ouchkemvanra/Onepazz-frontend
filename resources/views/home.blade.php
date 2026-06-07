<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnePazz — Corporate Wellness Platform</title>
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
    <a href="#plans" class="text-sm text-gray-500 hover:text-gray-800">Plans</a>
    <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.gyms') }}</a>
    <a href="{{ route('employer-register.create') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.register_company') }}</a>
    <a href="{{ route('gym-apply.create') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.partner') }}</a>
    @auth
        <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.dashboard') }}</a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.logout') }}</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('auth.sign_in') }}</a>
        <a href="{{ route('register') }}" class="text-sm bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700">{{ __('home.get_started') }}</a>
    @endauth

            {{-- Language toggle --}}
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

            {{-- Currency toggle --}}
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
<div class="bg-gradient-to-br from-gray-900 via-gray-800 to-teal-900 text-white py-24 px-6 text-center">
    <div class="inline-flex items-center gap-2 bg-teal-500/20 border border-teal-400/30 text-teal-300 text-xs font-medium px-4 py-1.5 rounded-full mb-6">
        🇰🇭 Built for Cambodia · បង្កើតសម្រាប់កម្ពុជា
    </div>
    <h1 class="text-5xl font-bold tracking-tight mb-4 leading-tight">
        Corporate Wellness,<br><span class="text-teal-400">Reimagined</span>
    </h1>
    <p class="text-gray-300 text-lg max-w-xl mx-auto mb-10">
        Connect your team with Cambodia's best gyms and fitness studios. One platform, hundreds of wellness options.
    </p>
    <div class="flex gap-3 justify-center flex-wrap">
        <a href="{{ route('employer-register.create') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold px-6 py-3 rounded-xl transition">Register your company →</a>
        <a href="{{ route('gyms.index') }}" class="bg-white/10 hover:bg-white/20 text-white font-medium px-6 py-3 rounded-xl border border-white/20 transition">{{ __('home.browse_gyms') }}</a>
    </div>

    {{-- Stats --}}
    <div class="flex justify-center gap-16 mt-16 pt-10 border-t border-white/10">
        <div><div class="text-3xl font-bold">120<span class="text-teal-400">+</span></div><div class="text-xs text-gray-400 mt-1">Partner Gyms</div></div>
        <div><div class="text-3xl font-bold">48<span class="text-teal-400">+</span></div><div class="text-xs text-gray-400 mt-1">Corporate Partners</div></div>
        <div><div class="text-3xl font-bold">5,200<span class="text-teal-400">+</span></div><div class="text-xs text-gray-400 mt-1">Active Members</div></div>
        <div><div class="text-3xl font-bold">7</div><div class="text-xs text-gray-400 mt-1">Provinces Covered</div></div>
    </div>
</div>

{{-- HOW IT WORKS --}}
<div class="max-w-5xl mx-auto px-6 py-20">
    <h2 class="text-3xl font-bold text-center mb-2">How it works</h2>
    <p class="text-center text-gray-500 mb-12">Three steps to company-wide wellness</p>
    <div class="grid grid-cols-3 gap-6">
        @foreach([
            ['1', 'Register your company', 'Sign up your organisation, choose a wellness plan, and complete bank transfer payment. Our admin team verifies within 24 hours.'],
            ['2', 'Invite your employees', 'Upload your staff list or invite individually. Each employee receives a membership card and access to the platform.'],
            ['3', 'Track & report', 'Monitor usage, generate CSV or PDF reports, and manage employee access from your dashboard — all in KHR and USD.'],
        ] as [$num, $title, $desc])
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="w-8 h-8 rounded-full bg-teal-50 border border-teal-200 text-teal-700 text-sm font-bold flex items-center justify-center mb-4">{{ $num }}</div>
            <h3 class="font-semibold mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</div>

{{-- PLANS --}}
<div id="plans" class="bg-white py-20 px-6">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold text-center mb-2">Corporate Plans</h2>
        <p class="text-center text-gray-500 mb-12">All plans include dual KHR / USD pricing. Payment via bank transfer.</p>
        <div class="grid grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div class="rounded-xl border {{ $plan->tier === 'silver' ? 'border-teal-500 border-2 relative' : 'border-gray-200' }} p-8">
                @if($plan->tier === 'silver')
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-teal-500 text-white text-xs font-semibold px-3 py-1 rounded-full">Most Popular</div>
                @endif
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">{{ $plan->name }}</div>
                <div class="text-4xl font-bold mb-1">{{ format_currency($plan->price_usd) }}</div>
                <div class="text-xs text-gray-400 mb-6">{{ __('common.per_month') }}</div>
                <ul class="space-y-2 mb-8">
                    @foreach($plan->features ?? [] as $feature)
                    <li class="text-sm text-gray-500 flex gap-2"><span class="text-teal-500 font-bold">✓</span>{{ $feature }}</li>
                    @endforeach
                </ul>
                <button class="w-full py-2.5 rounded-lg text-sm font-semibold border transition
                    {{ $plan->tier === 'silver' ? 'bg-teal-500 text-white border-teal-500 hover:bg-teal-600' : 'border-teal-500 text-teal-600 hover:bg-teal-50' }}">
                    Get {{ $plan->name }}
                </button>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- FEATURED GYMS --}}
<div id="gyms" class="max-w-5xl mx-auto px-6 py-20">
    <h2 class="text-3xl font-bold text-center mb-2">Featured Gyms</h2>
    <p class="text-center text-gray-500 mb-12">A sample of our Phnom Penh network</p>
    <div class="grid grid-cols-3 gap-6">
        @foreach($featuredGyms as $gym)
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md hover:border-teal-200 transition cursor-pointer">
            <div class="h-36 bg-teal-50 flex items-center justify-center text-5xl relative">
                {{ match(true) {
                    in_array('yoga', $gym->activity_types ?? [])      => '🧘',
                    in_array('kun_khmer', $gym->activity_types ?? []) => '🥊',
                    in_array('swimming', $gym->activity_types ?? [])  => '🏊',
                    default => '🏋️'
                } }}
                <span class="absolute top-3 right-3 text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $gym->tier === 'gold' ? 'bg-amber-100 text-amber-800' : ($gym->tier === 'silver' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                    {{ ucfirst($gym->tier) }}
                </span>
            </div>
            <div class="p-4">
                <h3 class="font-semibold mb-1">{{ $gym->name }}</h3>
                <p class="text-xs text-gray-400 mb-3">📍 {{ $gym->district }}, {{ $gym->city }}</p>
                <div class="flex flex-wrap gap-1 mb-3">
                    @foreach(array_slice($gym->activity_types ?? [], 0, 3) as $tag)
                    <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full">{{ ucwords(str_replace('_', ' ', $tag)) }}</span>
                    @endforeach
                </div>
                <div class="text-xs text-gray-400 flex items-center gap-1">
                    <span class="text-amber-400">★</span> {{ number_format($gym->average_rating, 1) }} ({{ $gym->review_count }} reviews)
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- FOOTER --}}
<footer class="border-t border-gray-200 py-8 text-center text-sm text-gray-400">
    © {{ date('Y') }} OnePazz Co., Ltd · Phnom Penh, Cambodia
</footer>

</body>
</html>
