<x-layouts.admin title="Payouts">

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Partner Payouts</h1>
        <p class="text-sm text-gray-400 mt-1">Revenue share calculations</p>
    </div>
</div>

<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="label">Year</label>
        <select name="year" class="input text-sm py-2">
            @for($y = now()->year; $y >= now()->year - 2; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>
    <div>
        <label class="label">Month</label>
        <select name="month" class="input text-sm py-2">
            @for($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::createFromDate(null, $m, 1)->format('F') }}</option>
            @endfor
        </select>
    </div>
    <button type="submit" class="btn-primary text-sm py-2">View</button>
    <a href="{{ route('admin.payouts.csv', ['year' => $year, 'month' => $month]) }}" class="btn-secondary text-sm py-2">
        Export CSV
    </a>
</form>

<div class="card overflow-hidden mb-6">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Partner</th>
                    <th class="table-head">Tier</th>
                    <th class="table-head text-right">Check-ins</th>
                    <th class="table-head text-right">Units</th>
                    <th class="table-head text-right">Value/Unit</th>
                    <th class="table-head text-right">Payout USD</th>
                    <th class="table-head text-right">OnePazz Cut</th>
                    <th class="table-head text-right">Payout KHR</th>
                    <th class="table-head text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $row)
                @php $savedPayout = $confirmed[$row['gym_id']] ?? null; @endphp
                <tr class="table-row">
                    <td class="table-cell font-semibold text-gray-800 text-sm">{{ $row['gym_name'] }}</td>
                    <td class="table-cell">
                        @if($row['tier'] === 'gold') <span class="badge-gold">Gold</span>
                        @elseif($row['tier'] === 'silver') <span class="badge-silver">Silver</span>
                        @else <span class="badge-bronze">Bronze</span>
                        @endif
                    </td>
                    <td class="table-cell text-right text-gray-600">{{ number_format($row['checkins']) }}</td>
                    <td class="table-cell text-right text-gray-600">{{ number_format($row['units']) }}</td>
                    <td class="table-cell text-right font-mono text-gray-600">${{ number_format($row['value_per_unit'], 4) }}</td>
                    <td class="table-cell text-right font-bold text-gray-800">${{ number_format($row['payout_usd'], 2) }}</td>
                    <td class="table-cell text-right text-gray-400">${{ number_format($row['onepazz_cut_usd'], 2) }}</td>
                    <td class="table-cell text-right text-gray-600">{{ number_format($row['payout_khr']) }} ៛</td>
                    <td class="table-cell text-center">
                        @if($savedPayout)
                            <span class="badge-green">{{ ucfirst($savedPayout->status) }}</span>
                        @else
                            <span class="badge-gray">Estimated</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-16 text-center">
                        <div class="text-4xl mb-3">💰</div>
                        <p class="text-gray-500 font-medium">No active gyms for this period</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($payouts->count() > 0)
            <tfoot>
                <tr class="border-t-2 border-gray-200 bg-gray-50">
                    <td colspan="5" class="py-3 px-5 font-bold text-gray-700">Totals</td>
                    <td class="py-3 px-5 text-right font-bold text-gray-800">${{ number_format($totalPayout, 2) }}</td>
                    <td class="py-3 px-5 text-right font-bold text-gray-500">${{ number_format($totalCut, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@if($payouts->count() > 0)
<form method="POST" action="{{ route('admin.payouts.confirm') }}">
    @csrf
    <input type="hidden" name="year" value="{{ $year }}">
    <input type="hidden" name="month" value="{{ $month }}">
    <button type="submit"
            onclick="return confirm('Mark all payouts as confirmed for {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}?')"
            class="btn-primary">
        Confirm All Payouts
    </button>
</form>
@endif

</x-layouts.admin>
