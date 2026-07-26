@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}" x-data="{ sidebarOpen: false }">

@php
    $employer = auth()->user()->adminForEmployer ?? null;
    $sub = $employer?->activeSubscription()->with('plan')->first();
@endphp

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="hidden lg:flex flex-col w-56 bg-white border-r border-gray-100 shrink-0">
        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-logo variant="dark" size="28" />
            </a>
        </div>

        {{-- Company info --}}
        @if($employer)
        <div class="px-4 py-4 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-800 truncate">{{ $employer->company_name }}</p>
            @if($sub?->plan)
            <span class="badge-brand mt-1 text-xs">{{ $sub->plan->name }}</span>
            @else
            <span class="badge-gray mt-1 text-xs">No plan</span>
            @endif
        </div>
        @endif

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $navActive = fn($route) => request()->routeIs($route) ? 'sidebar-link-active' : 'sidebar-link-inactive';
            @endphp
            <a href="{{ route('dashboard.index') }}" class="{{ $navActive('dashboard.index') }}">
                <span class="text-base">📊</span> {{ __('nav.dashboard') }}
            </a>
            <a href="{{ route('dashboard.employees.index') }}" class="{{ $navActive('dashboard.employees.*') }}">
                <span class="text-base">👥</span> {{ __('nav.employees') }}
            </a>
            <a href="{{ route('dashboard.billing.index') }}" class="{{ $navActive('dashboard.billing.*') }}">
                <span class="text-base">💳</span> {{ __('nav.billing') }}
            </a>
            <a href="{{ route('gyms.index') }}" class="{{ $navActive('gyms.*') }}">
                <span class="text-base">🏋️</span> {{ __('nav.gyms') }}
            </a>
        </nav>

        {{-- User + logout --}}
        <div class="px-3 py-4 border-t border-gray-100">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition-colors mb-1">
                <div class="w-7 h-7 rounded-lg bg-brand-500 text-white text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link-inactive w-full text-red-500 hover:text-red-600 hover:bg-red-50">
                    <span class="text-base">🚪</span> {{ __('nav.logout') }}
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Mobile top bar --}}
        <div class="lg:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-logo variant="dark" size="28" />
            </a>
            <button @click="sidebarOpen = !sidebarOpen" class="btn-ghost p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" />
        @endif

        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
