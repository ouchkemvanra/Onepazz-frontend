@props(['title' => 'Partner Portal'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — OnePazz Partner</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body class="bg-gray-50 font-sans">

@php $gym = auth()->user()->adminForGym ?? null; @endphp

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="hidden lg:flex flex-col w-56 bg-white border-r border-gray-100 shrink-0">
        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-gray-100">
            <a href="{{ route('gym-portal.index') }}" class="flex items-center gap-2">
                <x-logo variant="dark" size="28" />
            </a>
            <p class="text-xs text-gray-400 mt-0.5">Partner Portal</p>
        </div>

        {{-- Gym info --}}
        @if($gym)
        <div class="px-4 py-4 border-b border-gray-100">
            <p class="text-xs font-semibold text-gray-800 truncate">{{ $gym->name }}</p>
            @php $tier = $gym->tier ?? 'standard'; @endphp
            @if($tier === 'gold')
                <span class="badge-gold mt-1">Gold Partner</span>
            @elseif($tier === 'silver')
                <span class="badge-silver mt-1">Silver Partner</span>
            @else
                <span class="badge-bronze mt-1">Bronze Partner</span>
            @endif
        </div>
        @endif

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $gpActive = fn($route) => request()->routeIs($route) ? 'sidebar-link-active' : 'sidebar-link-inactive';
            @endphp
            <a href="{{ route('gym-portal.index') }}" class="{{ $gpActive('gym-portal.index') }}">
                <span class="text-base">📊</span> Overview
            </a>
            <a href="{{ route('gym-portal.earnings') }}" class="{{ $gpActive('gym-portal.earnings') }}">
                <span class="text-base">💰</span> Earnings
            </a>
            <a href="{{ route('gym-portal.bookings') }}" class="{{ $gpActive('gym-portal.bookings') }}">
                <span class="text-base">📅</span> Bookings
            </a>
            <a href="{{ route('gym-portal.classes') }}" class="{{ $gpActive('gym-portal.classes') }}">
                <span class="text-base">🏃</span> Classes
            </a>
            <a href="{{ route('gym-portal.reviews') }}" class="{{ $gpActive('gym-portal.reviews') }}">
                <span class="text-base">⭐</span> Reviews
            </a>
            <a href="{{ route('gym-portal.staff.index') }}" class="{{ $gpActive('gym-portal.staff*') }}">
                <span class="text-base">👥</span> Staff
            </a>
            <a href="{{ route('gym-portal.qr-code') }}" class="{{ $gpActive('gym-portal.qr-code') }}">
                <span class="text-base">📱</span> QR Code
            </a>
            <a href="{{ route('gym-portal.checkin-screen') }}" class="{{ $gpActive('gym-portal.checkin-screen') }}">
                <span class="text-base">🖥️</span> Check-in Screen
            </a>
            <a href="{{ route('gym-portal.profile') }}" class="{{ $gpActive('gym-portal.profile') }}">
                <span class="text-base">🏋️</span> Gym Profile
            </a>
        </nav>

        {{-- User --}}
        <div class="px-3 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3 px-3 py-2 mb-2">
                <div class="w-7 h-7 rounded-lg bg-brand-500 text-white text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-gray-400 truncate">Gym Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link-inactive w-full text-red-500 hover:text-red-600 hover:bg-red-50">
                    <span class="text-base">🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Mobile top bar --}}
        <div class="lg:hidden flex items-center justify-between px-4 py-3 bg-white border-b border-gray-100" x-data="{ open: false }">
            <a href="{{ route('gym-portal.index') }}" class="flex items-center gap-2">
                <x-logo variant="dark" size="28" />
            </a>
            <button @click="open = !open" class="btn-ghost p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Flash --}}
        @if(session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if(session('error'))
            <x-alert type="error" :message="session('error')" />
        @endif

        {{-- Top bar --}}
        <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100">
            <h1 class="text-lg font-bold text-gray-800">{{ $title }}</h1>
            @if($gym)
            <span class="badge-brand text-xs">{{ $gym->name }}</span>
            @endif
        </div>

        <main class="flex-1 overflow-y-auto p-6 lg:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
