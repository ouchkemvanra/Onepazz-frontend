<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview — {{ $gym->name }} — KhmerFit Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ $gym->name }}</h1>
        <p class="text-sm text-gray-400 mt-1">Partner Overview — {{ now()->format('F Y') }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Check-ins This Month</p>
            <p class="text-4xl font-bold text-teal-600">{{ number_format($checkinsThisMonth) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Payout Units Earned</p>
            <p class="text-4xl font-bold text-teal-600">{{ number_format($units) }}</p>
            <p class="text-xs text-gray-400 mt-1">÷ {{ $checkinsPerUnit }} per unit</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Est. Payout (USD)</p>
            <p class="text-4xl font-bold text-gray-700">${{ number_format($estimatedPayoutUsd, 2) }}</p>
            <p class="text-xs text-gray-400 mt-1">Confirmed at month close</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Today's Capacity</p>
            <p class="text-4xl font-bold {{ $dailyLimit && $todayCount >= $dailyLimit ? 'text-red-500' : 'text-gray-700' }}">
                {{ $todayCount }} / {{ $dailyLimit ?? '∞' }}
            </p>
            <p class="text-xs text-gray-400 mt-1">check-ins today</p>
        </div>
    </div>

    {{-- Payout Formula --}}
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 mb-8">
        <p class="text-sm font-semibold text-teal-800 mb-1">How your payout is calculated</p>
        <p class="text-sm text-teal-700">
            <strong>{{ number_format($checkinsThisMonth) }}</strong> check-ins
            ÷ <strong>{{ $checkinsPerUnit }}</strong> per unit
            = <strong>{{ number_format($units) }}</strong> units
            × <em>$X value/unit</em>
            × <strong>{{ 100 - $defaultSharePct }}%</strong>
            = <strong>your payout</strong>
        </p>
        <p class="text-xs text-teal-600 mt-1">Value per unit is calculated after month close based on total platform revenue.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Bar Chart --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Daily Check-ins (Last 7 Days)</h3>
            <canvas id="checkinChart" height="120"></canvas>
        </div>

        {{-- Today's Capacity Progress --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Today's Status</h3>
            @if($dailyLimit)
            @php $pct = min(100, round(($todayCount / $dailyLimit) * 100)); @endphp
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-500">{{ $todayCount }} of {{ $dailyLimit }} check-ins</span>
                    <span class="font-semibold {{ $pct >= 90 ? 'text-red-600' : 'text-teal-600' }}">{{ $pct }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all {{ $pct >= 90 ? 'bg-red-500' : 'bg-teal-500' }}" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @if($pct >= 100)
            <p class="text-sm text-red-600 font-medium">Daily capacity reached — new check-ins are blocked.</p>
            @elseif($pct >= 80)
            <p class="text-sm text-orange-600">Approaching daily limit.</p>
            @else
            <p class="text-sm text-gray-400">Capacity available.</p>
            @endif
            @else
            <div class="flex items-center gap-3 mt-4">
                <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center text-2xl font-bold text-teal-600">{{ $todayCount }}</div>
                <p class="text-gray-500 text-sm">Check-ins today — no daily limit set.</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent Check-ins --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Recent Check-ins</h3>
        @if($recentCheckins->isEmpty())
        <p class="text-sm text-gray-400">No check-ins yet.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 text-gray-400 font-medium">Card No.</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Plan Tier</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Date</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Time</th>
                        <th class="text-left py-2 text-gray-400 font-medium">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentCheckins as $ci)
                    @php $card = $ci->employee?->membership_card_no ?? '—'; @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-2 font-mono text-gray-700">{{ $card }}</td>
                        <td class="py-2">
                            @php $tier = $ci->employee?->subscription?->plan?->tier ?? '—'; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $tier === 'gold' ? 'bg-yellow-100 text-yellow-700' : ($tier === 'silver' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($tier) }}
                            </span>
                        </td>
                        <td class="py-2 text-gray-600">{{ $ci->checked_in_at->format('d M Y') }}</td>
                        <td class="py-2 text-gray-600">{{ $ci->checked_in_at->format('H:i') }}</td>
                        <td class="py-2 text-gray-500">{{ $ci->duration_minutes ? $ci->duration_minutes . ' min' : '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

<script>
const ctx = document.getElementById('checkinChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json(collect($chartData)->pluck('label')),
        datasets: [{
            label: 'Check-ins',
            data: @json(collect($chartData)->pluck('count')),
            backgroundColor: 'rgba(13,148,136,0.7)',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
</body>
</html>
