<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $gym->name }} — Admin — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit Admin
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.gyms.index') }}" class="text-sm text-teal-600 font-medium">← Back to Gyms</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="text-sm text-gray-500 hover:text-gray-800">Logout</button></form>
        </div>
    </div>
</nav>

<div class="max-w-2xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $gym->name }}</h1>
        <p class="text-sm text-gray-400 mt-1">Partner settings</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.gyms.update', $gym) }}">
        @csrf @method('PATCH')
        <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tier</label>
                    <select name="tier" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['bronze','silver','gold'] as $t)
                        <option value="{{ $t }}" {{ old('tier', $gym->tier) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Monthly Fee (USD)</label>
                    <input type="number" step="0.01" min="0" name="monthly_fee_usd" value="{{ old('monthly_fee_usd', $gym->monthly_fee_usd) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">KhmerFit Cut % (override)</label>
                    <input type="number" step="0.01" min="0" max="100" name="revenue_share_pct" value="{{ old('revenue_share_pct', $gym->revenue_share_pct) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Platform default is {{ \App\Models\PlatformConfig::get('revenue_share_pct_default', 30) }}%</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Daily Capacity Limit</label>
                    <input type="number" min="1" name="daily_capacity_limit" value="{{ old('daily_capacity_limit', $gym->daily_capacity_limit) }}" placeholder="Blank = unlimited" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Check-in Radius (meters)</label>
                    <input type="number" min="10" max="5000" name="checkin_radius_meters" value="{{ old('checkin_radius_meters', $gym->checkin_radius_meters ?? 50) }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Members must be within this distance to QR check in.</p>
                </div>
            </div>

            {{-- QR Token --}}
            <div class="border-t border-gray-100 pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">QR Token</label>
                <div class="flex items-center gap-3">
                    <input type="text" readonly value="{{ $gym->qr_code ?? 'Not generated' }}" class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-mono text-gray-500">
                    <form method="POST" action="{{ route('admin.gyms.regenerate-qr', $gym) }}">
                        @csrf
                        <button type="submit" class="border border-gray-200 px-3 py-2 rounded-lg text-sm hover:bg-gray-50 transition whitespace-nowrap">Regenerate</button>
                    </form>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">Save Changes</button>
                <a href="{{ route('admin.gyms.index') }}" class="border border-gray-200 px-6 py-2 rounded-lg hover:bg-gray-50 transition text-sm text-gray-600">Cancel</a>
            </div>
        </div>
    </form>
</div>
</body>
</html>
