<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payouts — Admin — OnePazz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            OnePazz Admin
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payments</a>
            <a href="{{ route('admin.gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Gyms</a>
            <a href="{{ route('admin.payouts.index') }}" class="text-sm font-semibold text-teal-600">Payouts</a>
            <a href="{{ route('admin.settings') }}" class="text-sm text-gray-500 hover:text-gray-800">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="text-sm text-gray-500 hover:text-gray-800">Logout</button></form>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Partner Payouts</h1>
            <p class="text-sm text-gray-400 mt-1">Revenue share calculations</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    {{-- Month Selector --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
            <select name="year" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                @for($y = now()->year; $y >= now()->year - 2; $y--)
                <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Month</label>
            <select name="month" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}</option>
                @endfor
            </select>
        </div>
        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition">View</button>
        <a href="{{ route('admin.payouts.csv', ['year' => $year, 'month' => $month]) }}"
           class="border border-gray-200 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition text-gray-600">
            Export CSV
        </a>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Partner</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Tier</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Check-ins</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Units</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Value/Unit</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Payout USD</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">OnePazz Cut</th>
                        <th class="text-right py-3 px-3 text-gray-400 font-medium">Payout KHR</th>
                        <th class="text-center py-3 px-3 text-gray-400 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payouts as $row)
                    @php $savedPayout = $confirmed[$row['gym_id']] ?? null; @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-3 font-medium text-gray-800">{{ $row['gym_name'] }}</td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $row['tier'] === 'gold' ? 'bg-yellow-100 text-yellow-700' : ($row['tier'] === 'silver' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($row['tier']) }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-right text-gray-600">{{ number_format($row['checkins']) }}</td>
                        <td class="py-3 px-3 text-right text-gray-600">{{ number_format($row['units']) }}</td>
                        <td class="py-3 px-3 text-right font-mono text-gray-600">${{ number_format($row['value_per_unit'], 4) }}</td>
                        <td class="py-3 px-3 text-right font-semibold text-gray-800">${{ number_format($row['payout_usd'], 2) }}</td>
                        <td class="py-3 px-3 text-right text-gray-500">${{ number_format($row['onepazz_cut_usd'], 2) }}</td>
                        <td class="py-3 px-3 text-right text-gray-600">{{ number_format($row['payout_khr']) }} ៛</td>
                        <td class="py-3 px-3 text-center">
                            @if($savedPayout)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">{{ ucfirst($savedPayout->status) }}</span>
                            @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Estimated</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="py-8 text-center text-gray-400 text-sm">No active gyms for this period.</td></tr>
                    @endforelse
                </tbody>
                @if($payouts->count() > 0)
                <tfoot>
                    <tr class="border-t-2 border-gray-200 bg-gray-50">
                        <td colspan="5" class="py-3 px-3 font-semibold text-gray-700">Totals</td>
                        <td class="py-3 px-3 text-right font-bold text-gray-800">${{ number_format($totalPayout, 2) }}</td>
                        <td class="py-3 px-3 text-right font-bold text-gray-600">${{ number_format($totalCut, 2) }}</td>
                        <td class="py-3 px-3"></td>
                        <td class="py-3 px-3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    @if($payouts->count() > 0)
    <form method="POST" action="{{ route('admin.payouts.confirm') }}">
        @csrf
        <input type="hidden" name="year"  value="{{ $year }}">
        <input type="hidden" name="month" value="{{ $month }}">
        <button type="submit"
                onclick="return confirm('Mark all payouts as confirmed for {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}?')"
                class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">
            Confirm All Payouts
        </button>
    </form>
    @endif
</div>
</body>
</html>
