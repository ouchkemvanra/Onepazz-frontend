<x-layouts.gym-portal title="Overview">

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ $gym->name }}</h1>
    <p class="text-sm text-gray-400 mt-1">Partner Overview — {{ now()->format('F Y') }}</p>
</div>

{{-- KPI cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Check-ins This Month</p>
        <p class="text-3xl font-bold text-brand-600">{{ number_format($checkinsThisMonth) }}</p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Payout Units Earned</p>
        <p class="text-3xl font-bold text-brand-600">{{ number_format($units) }}</p>
        <p class="text-xs text-gray-400 mt-1">÷ {{ $checkinsPerUnit }} per unit</p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Est. Payout (USD)</p>
        <p class="text-3xl font-bold text-gray-800">${{ number_format($estimatedPayoutUsd, 2) }}</p>
        <p class="text-xs text-gray-400 mt-1">Confirmed at month close</p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Today's Capacity</p>
        <p class="text-3xl font-bold {{ $dailyLimit && $todayCount >= $dailyLimit ? 'text-red-500' : 'text-gray-800' }}">
            {{ $todayCount }} / {{ $dailyLimit ?? '∞' }}
        </p>
        <p class="text-xs text-gray-400 mt-1">check-ins today</p>
    </div>
</div>

{{-- Payout formula --}}
<div class="bg-brand-50 border border-brand-200 rounded-2xl p-5 mb-6">
    <p class="text-sm font-bold text-brand-800 mb-1">How your payout is calculated</p>
    <p class="text-sm text-brand-700">
        <strong>{{ number_format($checkinsThisMonth) }}</strong> check-ins
        ÷ <strong>{{ $checkinsPerUnit }}</strong> per unit
        = <strong>{{ number_format($units) }}</strong> units
        × <em>$X value/unit</em>
        × <strong>{{ 100 - $defaultSharePct }}%</strong>
        = <strong>your payout</strong>
    </p>
    <p class="text-xs text-brand-500 mt-1">Value per unit is calculated after month close based on total platform revenue.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- Chart --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Daily Check-ins (Last 7 Days)</h3>
        <canvas id="checkinChart" height="120"></canvas>
    </div>

    {{-- Today's capacity --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-5">Today's Status</h3>
        @if($dailyLimit)
        @php $pct = min(100, round(($todayCount / $dailyLimit) * 100)); @endphp
        <div class="mb-3">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-500">{{ $todayCount }} of {{ $dailyLimit }} check-ins</span>
                <span class="font-bold {{ $pct >= 90 ? 'text-red-500' : 'text-brand-600' }}">{{ $pct }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3">
                <div class="h-3 rounded-full transition-all {{ $pct >= 90 ? 'bg-red-500' : 'bg-brand-500' }}"
                    style="width:{{ $pct }}%"></div>
            </div>
        </div>
        @if($pct >= 100)
        <p class="text-sm text-red-600 font-semibold">Daily capacity reached — new check-ins are blocked.</p>
        @elseif($pct >= 80)
        <p class="text-sm text-warm-600 font-medium">Approaching daily limit.</p>
        @else
        <p class="text-sm text-gray-400">Capacity available.</p>
        @endif
        @else
        <div class="flex items-center gap-4 mt-2">
            <div class="w-14 h-14 rounded-2xl bg-brand-50 border border-brand-200 flex items-center justify-center text-2xl font-bold text-brand-600">
                {{ $todayCount }}
            </div>
            <p class="text-gray-500 text-sm">Check-ins today — no daily limit set.</p>
        </div>
        @endif
    </div>
</div>

{{-- Recent check-ins --}}
<div class="card">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">Recent Check-ins</h3>
    </div>
    @if($recentCheckins->isEmpty())
    <div class="text-center py-16">
        <div class="text-4xl mb-3">📋</div>
        <p class="text-gray-500 font-medium">No check-ins yet</p>
        <p class="text-gray-400 text-sm mt-1">Check-ins will appear here in real time.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Card No.</th>
                    <th class="table-head">Plan Tier</th>
                    <th class="table-head">Date</th>
                    <th class="table-head">Time</th>
                    <th class="table-head">Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentCheckins as $ci)
                <tr class="table-row">
                    <td class="table-cell font-mono text-gray-700 text-sm">{{ $ci->employee?->membership_card_no ?? '—' }}</td>
                    <td class="table-cell">
                        @php $tier = $ci->employee?->subscription?->plan?->tier ?? null; @endphp
                        @if($tier === 'gold')
                            <span class="badge-gold">Gold</span>
                        @elseif($tier === 'silver')
                            <span class="badge-silver">Silver</span>
                        @elseif($tier)
                            <span class="badge-bronze">Bronze</span>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="table-cell text-gray-600 text-sm">{{ $ci->checked_in_at->format('d M Y') }}</td>
                    <td class="table-cell text-gray-600 text-sm">{{ $ci->checked_in_at->format('H:i') }}</td>
                    <td class="table-cell text-gray-400 text-sm">{{ $ci->duration_minutes ? $ci->duration_minutes . ' min' : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
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
            backgroundColor: 'rgba(14,168,138,0.7)',
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

</x-layouts.gym-portal>
