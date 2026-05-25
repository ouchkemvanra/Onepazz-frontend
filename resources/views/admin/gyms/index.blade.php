<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Management — Admin — KhmerFit</title>
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
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payments</a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Applications</a>
            <a href="{{ route('admin.gyms.index') }}" class="text-sm font-semibold text-teal-600">Gyms</a>
            <a href="{{ route('admin.payouts.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payouts</a>
            <a href="{{ route('admin.settings') }}" class="text-sm text-gray-500 hover:text-gray-800">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="text-sm text-gray-500 hover:text-gray-800">Logout</button></form>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Gym Management</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $gyms->total() }} partners</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Name</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Tier</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Status</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Monthly Fee</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Rev. Share %</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Partner Since</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gyms as $gym)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-3">
                            <p class="font-medium text-gray-800">{{ $gym->name }}</p>
                            <p class="text-xs text-gray-400">{{ $gym->city }}</p>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $gym->tier === 'gold' ? 'bg-yellow-100 text-yellow-700' : ($gym->tier === 'silver' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($gym->tier) }}
                            </span>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $gym->status === 'active' ? 'bg-green-100 text-green-700' : ($gym->status === 'suspended' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-500') }}">
                                {{ ucfirst($gym->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-right font-mono text-gray-700">${{ number_format($gym->monthly_fee_usd, 2) }}</td>
                        <td class="py-3 px-3 text-right text-gray-700">{{ $gym->revenue_share_pct }}%</td>
                        <td class="py-3 px-3 text-gray-500 text-xs">{{ $gym->partner_since?->format('d M Y') ?? '—' }}</td>
                        <td class="py-3 px-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.gyms.edit', $gym) }}" class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50 transition">Edit</a>
                                @if($gym->status === 'active')
                                <form method="POST" action="{{ route('admin.gyms.suspend', $gym) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50 transition">Suspend</button>
                                </form>
                                @elseif($gym->status === 'suspended')
                                <form method="POST" action="{{ route('admin.gyms.activate', $gym) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs border border-green-200 text-green-600 px-2 py-1 rounded hover:bg-green-50 transition">Activate</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $gyms->links() }}</div>
    </div>
</div>
</body>
</html>
