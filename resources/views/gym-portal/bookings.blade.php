<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings — {{ $gym->name }} — OnePazz Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Bookings</h1>
        <p class="text-sm text-gray-400 mt-1">Class booking management</p>
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl border border-gray-200 p-4 mb-6 flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
            <input type="date" name="date" value="{{ $date }}" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Class</label>
            <select name="class_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                <option value="">All Classes</option>
                @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition">Filter</button>
    </form>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">
                {{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}
                — {{ $bookings->count() }} booking{{ $bookings->count() !== 1 ? 's' : '' }}
            </h3>
        </div>

        @if($bookings->isEmpty())
        <p class="text-sm text-gray-400 text-center py-8">No bookings for this date/class.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-2 text-gray-400 font-medium">Card No.</th>
                        <th class="text-left py-3 px-2 text-gray-400 font-medium">Plan Tier</th>
                        <th class="text-left py-3 px-2 text-gray-400 font-medium">Class</th>
                        <th class="text-left py-3 px-2 text-gray-400 font-medium">Status</th>
                        <th class="text-left py-3 px-2 text-gray-400 font-medium">Booked At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $b)
                    @php $card = $b->user->employees->first()?->membership_card_no ?? '—'; @endphp
                    <tr class="border-b border-gray-50 hover:bg-gray-50">
                        <td class="py-3 px-2 font-mono text-gray-700">{{ $card }}</td>
                        <td class="py-3 px-2">
                            @php $tier = $b->user->employees->first()?->subscription?->plan?->tier ?? '—'; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $tier === 'gold' ? 'bg-yellow-100 text-yellow-700' : ($tier === 'silver' ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-700') }}">
                                {{ ucfirst($tier) }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-gray-700">{{ $b->gymClass->name }}</td>
                        <td class="py-3 px-2">
                            @php $statusMap = ['confirmed'=>'bg-green-100 text-green-700','waitlisted'=>'bg-orange-100 text-orange-700','cancelled'=>'bg-gray-100 text-gray-500']; @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusMap[$b->status] ?? '' }}">
                                {{ ucfirst($b->status) }}
                            </span>
                        </td>
                        <td class="py-3 px-2 text-gray-500">{{ $b->booked_at->format('H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
</body>
</html>
