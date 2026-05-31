<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earnings — {{ $gym->name }} — KhmerFit Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-6xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Earnings</h1>
        <p class="text-sm text-gray-400 mt-1">Last 6 months revenue share history</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <p class="text-sm text-blue-700">
            Payouts are processed on the <strong>5th of each month</strong> for the previous month.
            Your KhmerFit cut is <strong>{{ $gym->effectiveRevenueSharePct() }}%</strong>.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-gray-400 font-medium">Month</th>
                        <th class="text-right py-3 px-4 text-gray-400 font-medium">Check-ins</th>
                        <th class="text-right py-3 px-4 text-gray-400 font-medium">Units</th>
                        <th class="text-right py-3 px-4 text-gray-400 font-medium">Your Payout (USD)</th>
                        <th class="text-right py-3 px-4 text-gray-400 font-medium">KhmerFit Cut (USD)</th>
                        <th class="text-right py-3 px-4 text-gray-400 font-medium">Payout (KHR)</th>
                        <th class="text-center py-3 px-4 text-gray-400 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($months as $row)
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-4 font-medium text-gray-700">{{ $row['label'] }}</td>
                        <td class="py-3 px-4 text-right text-gray-600">{{ number_format($row['checkins']) }}</td>
                        <td class="py-3 px-4 text-right text-gray-600">{{ number_format($row['units']) }}</td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800">${{ number_format($row['payout_usd'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-gray-500">${{ number_format($row['khmerfit_cut'], 2) }}</td>
                        <td class="py-3 px-4 text-right text-gray-600">{{ number_format($row['payout_khr']) }} ៛</td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $statusMap = ['confirmed' => 'bg-green-100 text-green-700', 'paid' => 'bg-teal-100 text-teal-700', 'current' => 'bg-blue-100 text-blue-700', 'estimated' => 'bg-gray-100 text-gray-500'];
                                $cls = $statusMap[$row['status']] ?? 'bg-gray-100 text-gray-500';
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cls }}">
                                {{ ucfirst($row['status']) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
