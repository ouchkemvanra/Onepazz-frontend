<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings — Admin — KhmerFit</title>
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
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payments</a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Gym Applications</a>
            <a href="{{ route('admin.gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Gyms</a>
            <a href="{{ route('admin.payouts.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payouts</a>
            <a href="{{ route('admin.settings') }}" class="text-sm font-medium text-teal-600">Settings</a>
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

<div class="max-w-4xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Platform Settings</h1>
        <p class="text-sm text-gray-400 mt-1">Configure platform-wide settings</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Exchange Rate Settings --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold mb-4">Currency Exchange Rate</h3>
        <p class="text-sm text-gray-500 mb-6">Set the USD to KHR exchange rate used for invoice calculations.</p>

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">USD to KHR Rate</label>
                <div class="flex items-center gap-3">
                    <span class="text-gray-500">1 USD =</span>
                    <input
                        type="number"
                        name="khr_rate"
                        value="{{ old('khr_rate', $khrRate) }}"
                        step="0.01"
                        min="1"
                        max="50000"
                        required
                        class="border border-gray-200 rounded-lg px-4 py-2 w-32 text-right font-mono"
                    >
                    <span class="text-gray-500">៛</span>
                </div>
                <p class="text-xs text-gray-400 mt-2">Current rate: 1 USD = {{ number_format($khrRate) }} KHR</p>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                    Save Changes
                </button>
                <button type="button" onclick="window.location.reload()" class="border border-gray-200 px-6 py-2 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    {{-- Check-in Radius Settings --}}
    <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold mb-1">Check-in Radius Settings</h3>
        <p class="text-sm text-gray-500 mb-6">Default radius used when a gym doesn't have a custom setting.</p>
        <form method="POST" action="{{ route('admin.settings.checkin-radius') }}">
            @csrf
            <div class="flex items-center gap-3 mb-4">
                <label class="text-sm font-medium text-gray-700">Default Radius</label>
                <input type="number" name="checkin_radius_default" value="{{ old('checkin_radius_default', $checkinRadiusDefault) }}" min="10" max="5000"
                       class="border border-gray-200 rounded-lg px-4 py-2 w-28 font-mono">
                <span class="text-gray-500 text-sm">meters</span>
            </div>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">Save</button>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-700">
            <strong>Note:</strong> This exchange rate will be used for all new invoices. Existing invoices will retain their original exchange rate.
        </p>
    </div>

    {{-- Revenue Share Settings --}}
    <div class="mt-8 bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold mb-1">Revenue Share Settings</h3>
        <p class="text-sm text-gray-500 mb-6">Configure how many check-ins equal one payout unit per tier, and the default KhmerFit cut.</p>

        <form method="POST" action="{{ route('admin.settings.revenue-config') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-ins per unit — Gold</label>
                    <input type="number" name="checkins_per_unit_gold" value="{{ old('checkins_per_unit_gold', $checkinsGold) }}" min="1" required
                           class="border border-gray-200 rounded-lg px-4 py-2 w-32 font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-ins per unit — Silver</label>
                    <input type="number" name="checkins_per_unit_silver" value="{{ old('checkins_per_unit_silver', $checkinsSilver) }}" min="1" required
                           class="border border-gray-200 rounded-lg px-4 py-2 w-32 font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-ins per unit — Bronze</label>
                    <input type="number" name="checkins_per_unit_bronze" value="{{ old('checkins_per_unit_bronze', $checkinsBronze) }}" min="1" required
                           class="border border-gray-200 rounded-lg px-4 py-2 w-32 font-mono">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Default KhmerFit Cut (%)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="revenue_share_pct_default" value="{{ old('revenue_share_pct_default', $revenueShareDefault) }}" min="0" max="100" step="0.01" required
                               class="border border-gray-200 rounded-lg px-4 py-2 w-32 font-mono">
                        <span class="text-gray-500">%</span>
                    </div>
                </div>
            </div>
            <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                Save Revenue Config
            </button>
        </form>
    </div>

</div>

</body>
</html>
