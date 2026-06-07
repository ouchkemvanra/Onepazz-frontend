<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — OnePazz</title>
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
        <div class="flex items-center gap-6">
            @if(auth()->user()->isEmployerAdmin())
                <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.dashboard') }}</a>
            @endif
            <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.gyms') }}</a>
            <a href="{{ route('profile.index') }}" class="text-sm font-medium text-teal-600">{{ __('nav.profile') }}</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.logout') }}</button>
            </form>
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
            <div class="w-8 h-8 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-semibold">
                {{ strtoupper(substr($user->full_name, 0, 2)) }}
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('profile.profile') }}</h1>
        <p class="text-sm text-gray-400 mt-1">{{ __('profile.personal_info') }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-3 gap-6">

        {{-- Left Column: Profile Info --}}
        <div class="space-y-6">

            {{-- User Info Card --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 rounded-full bg-teal-600 text-white text-2xl flex items-center justify-center font-semibold">
                        {{ strtoupper(substr($user->full_name, 0, 2)) }}
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg">{{ $user->full_name }}</h3>
                        @if($user->full_name_kh)
                        <p class="text-sm text-gray-400">{{ $user->full_name_kh }}</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email</span>
                        <span class="text-gray-800">{{ $user->email }}</span>
                    </div>
                    @if($user->phone)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Phone</span>
                        <span class="text-gray-800">{{ $user->phone }}</span>
                    </div>
                    @endif
                    @if($user->date_of_birth)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Date of Birth</span>
                        <span class="text-gray-800">{{ $user->date_of_birth->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($user->gender)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Gender</span>
                        <span class="text-gray-800">{{ ucfirst($user->gender) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-400">Role</span>
                        <span class="text-gray-800">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</span>
                    </div>
                </div>
            </div>

            {{-- Membership Card --}}
            @if($employee)
            <div class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-xl p-6 text-white shadow-lg">
                <div class="mb-4">
                    <p class="text-teal-100 text-xs uppercase tracking-wide">Membership Card</p>
                    <p class="text-2xl font-bold mt-1">{{ $employee->membership_card_no }}</p>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-teal-100">Company</span>
                        <span class="font-semibold">{{ $employee->employer->company_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-teal-100">Plan</span>
                        <span class="font-semibold">{{ $employee->subscription?->plan?->name ?? 'No Plan' }}</span>
                    </div>
                    @if($employee->department)
                    <div class="flex justify-between">
                        <span class="text-teal-100">Department</span>
                        <span class="font-semibold">{{ $employee->department }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-teal-100">Status</span>
                        <span>
                            @if($employee->status === 'active')
                                <span class="bg-green-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">Active</span>
                            @else
                                <span class="bg-red-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">{{ ucfirst($employee->status) }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-200 p-6 text-center">
                <p class="text-sm text-gray-400">No membership card assigned</p>
            </div>
            @endif

        </div>

        {{-- Right Column: Activity --}}
        <div class="col-span-2 space-y-6">

            {{-- Check-in History --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">{{ __('profile.checkin_history') }}</h3>

                @if($checkins->count() > 0)
                <div class="space-y-3">
                    @foreach($checkins as $checkin)
                    <div class="flex items-center justify-between border-b border-gray-50 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-50 rounded-lg flex items-center justify-center text-teal-600">
                                🏋️
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $checkin->gym->name }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $checkin->checked_in_at->format('d M Y, H:i') }}
                                    @if($checkin->gymClass)
                                        · {{ $checkin->gymClass->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if($checkin->duration_minutes)
                        <span class="text-sm text-gray-500">{{ $checkin->duration_minutes }} min</span>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-400 text-center py-8">No check-ins yet. Visit a gym to get started!</p>
                @endif
            </div>

            {{-- Saved Gyms --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Saved Gyms</h3>

                @if($savedGyms->count() > 0)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($savedGyms as $gym)
                    <a href="{{ route('gyms.show', $gym) }}" class="border border-gray-200 rounded-lg p-4 hover:border-teal-300 transition">
                        <h4 class="font-medium text-gray-800 mb-1">{{ $gym->name }}</h4>
                        @if($gym->name_kh)
                        <p class="text-xs text-gray-400 mb-2">{{ $gym->name_kh }}</p>
                        @endif
                        <p class="text-xs text-gray-500">{{ $gym->district }}, {{ $gym->city }}</p>
                        <p class="text-xs text-gray-400 mt-2">Saved {{ $gym->pivot->saved_at ? \Carbon\Carbon::parse($gym->pivot->saved_at)->diffForHumans() : '' }}</p>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-400 text-center py-8">No saved gyms yet. <a href="{{ route('gyms.index') }}" class="text-teal-600 hover:underline">Browse gyms</a></p>
                @endif
            </div>

        </div>

    </div>

</div>

</body>
</html>
