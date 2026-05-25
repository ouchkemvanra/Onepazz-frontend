<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit Admin
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-teal-600">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payments</a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Gym Applications</a>
            <a href="{{ route('admin.gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Gyms</a>
            <a href="{{ route('admin.payouts.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payouts</a>
            <a href="{{ route('admin.settings') }}" class="text-sm text-gray-500 hover:text-gray-800">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800">Logout</button>
            </form>
            <div class="w-8 h-8 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-semibold">
                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>
        <p class="text-sm text-gray-400 mt-1">Platform administration and management</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <a href="{{ route('admin.payments.index') }}" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-teal-300 transition">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Pending Payments</p>
            <p class="text-4xl font-bold text-orange-500">{{ $stats['pending_payments'] }}</p>
            <p class="text-xs text-gray-400 mt-2">→ Review payments</p>
        </a>

        <a href="{{ route('admin.gym-applications.index') }}" class="bg-white rounded-xl border border-gray-200 p-6 hover:border-teal-300 transition">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Pending Gym Applications</p>
            <p class="text-4xl font-bold text-orange-500">{{ $stats['pending_gym_applications'] }}</p>
            <p class="text-xs text-gray-400 mt-2">→ Review applications</p>
        </a>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold mb-4">Quick Actions</h3>
        <div class="grid grid-cols-3 gap-3">
            <a href="{{ route('admin.payments.index') }}" class="text-sm border border-gray-200 bg-white px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                💳 Manage Payments
            </a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm border border-gray-200 bg-white px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                🏋️ Gym Applications
            </a>
            <a href="{{ route('admin.gyms.index') }}" class="text-sm border border-gray-200 bg-white px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                🏢 Manage Gyms
            </a>
            <a href="{{ route('admin.payouts.index') }}" class="text-sm border border-gray-200 bg-white px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                💰 Payouts
            </a>
            <a href="{{ route('admin.settings') }}" class="text-sm border border-gray-200 bg-white px-4 py-3 rounded-lg hover:bg-gray-50 transition text-center">
                ⚙️ Settings
            </a>
        </div>
    </div>

</div>

</body>
</html>
