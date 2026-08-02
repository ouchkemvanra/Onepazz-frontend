@props(['title' => 'Admin'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} — Admin — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: true }">

<div class="flex h-screen overflow-hidden">

    {{-- Dark sidebar --}}
    <aside class="hidden lg:flex flex-col w-56 bg-slate-900 shrink-0">
        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <x-logo variant="white-full" size="28" />
            </a>
            <p class="text-xs text-slate-500 mt-1">Admin Panel</p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            @php
                $adminNavActive = fn($route) => request()->routeIs($route)
                    ? 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold bg-brand-500/20 text-brand-400'
                    : 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-white transition-all';
            @endphp
            <a href="{{ route('admin.dashboard') }}" class="{{ $adminNavActive('admin.dashboard') }}">
                <span>📊</span> Overview
            </a>
            <a href="{{ route('admin.payments.index') }}" class="{{ $adminNavActive('admin.payments.*') }}">
                <span>💰</span> Payments
            </a>
            <a href="{{ route('admin.employers.index') }}" class="{{ $adminNavActive('admin.employers.*') }}">
                <span>🏢</span> Employers
            </a>
            <a href="{{ route('admin.gyms.index') }}" class="{{ $adminNavActive('admin.gyms.*') }}">
                <span>🏋️</span> Gyms
            </a>
            <a href="{{ route('admin.gym-applications.index') }}" class="{{ $adminNavActive('admin.gym-applications.*') }}">
                <span>📋</span> Applications
            </a>
            <a href="{{ route('admin.payouts.index') }}" class="{{ $adminNavActive('admin.payouts.*') }}">
                <span>💸</span> Payouts
            </a>
            <a href="{{ route('admin.settings') }}" class="{{ $adminNavActive('admin.settings') }}">
                <span>⚙️</span> Settings
            </a>
        </nav>

        {{-- User --}}
        <div class="px-3 py-4 border-t border-slate-800">
            <div class="flex items-center gap-3 px-3 py-2 mb-2">
                <div class="w-7 h-7 rounded-lg bg-brand-500 text-white text-xs font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs text-slate-500 truncate">Platform Admin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-2 w-full px-3 py-2 rounded-xl text-xs text-slate-400 hover:text-red-400 hover:bg-slate-800 transition-all">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100">
            <h1 class="text-lg font-bold text-gray-800">{{ $title }}</h1>
            <div class="flex items-center gap-3">
                <span class="badge-brand">Admin</span>
            </div>
        </div>

        {{-- Flash --}}
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
