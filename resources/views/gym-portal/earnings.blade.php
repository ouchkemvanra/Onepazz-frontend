<x-layouts.gym-portal title="Earnings">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Earnings</h1>
    <p class="text-sm text-gray-400 mt-1">Last 6 months revenue share history</p>
</div>

<div class="bg-brand-50 border border-brand-200 rounded-2xl p-5 mb-6">
    <p class="text-sm text-brand-700">
        Payouts are processed on the <strong class="text-brand-900">5th of each month</strong> for the previous month.
        Your OnePazz cut is <strong class="text-brand-900">{{ $gym->effectiveRevenueSharePct() }}%</strong>.
    </p>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Month</th>
                    <th class="table-head text-right">Check-ins</th>
                    <th class="table-head text-right">Units</th>
                    <th class="table-head text-right">Your Payout (USD)</th>
                    <th class="table-head text-right">OnePazz Cut (USD)</th>
                    <th class="table-head text-right">Payout (KHR)</th>
                    <th class="table-head text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($months as $row)
                <tr class="table-row">
                    <td class="table-cell font-semibold text-gray-800">{{ $row['label'] }}</td>
                    <td class="table-cell text-right text-gray-600">{{ number_format($row['checkins']) }}</td>
                    <td class="table-cell text-right text-gray-600">{{ number_format($row['units']) }}</td>
                    <td class="table-cell text-right font-bold text-gray-800">${{ number_format($row['payout_usd'], 2) }}</td>
                    <td class="table-cell text-right text-gray-400">${{ number_format($row['onepazz_cut'], 2) }}</td>
                    <td class="table-cell text-right text-gray-600">{{ number_format($row['payout_khr']) }} ៛</td>
                    <td class="table-cell text-center">
                        @if($row['status'] === 'paid')
                            <span class="badge-green">Paid</span>
                        @elseif($row['status'] === 'confirmed')
                            <span class="badge-brand">Confirmed</span>
                        @elseif($row['status'] === 'current')
                            <span class="badge-amber">Current</span>
                        @else
                            <span class="badge-gray">Estimated</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

</x-layouts.gym-portal>
