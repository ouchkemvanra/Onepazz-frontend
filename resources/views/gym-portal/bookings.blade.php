<x-layouts.gym-portal title="Bookings">

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Bookings</h1>
    <p class="text-sm text-gray-400 mt-1">Class booking management</p>
</div>

{{-- Filters --}}
<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-4 items-end">
    <div>
        <label class="label">Date</label>
        <input type="date" name="date" value="{{ $date }}" class="input text-sm py-2">
    </div>
    <div>
        <label class="label">Class</label>
        <select name="class_id" class="input text-sm py-2">
            <option value="">All Classes</option>
            @foreach($classes as $c)
            <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-primary text-sm py-2">Filter</button>
</form>

<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">
            {{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}
            <span class="text-gray-400 font-normal ml-2">— {{ $bookings->count() }} booking{{ $bookings->count() !== 1 ? 's' : '' }}</span>
        </h3>
    </div>

    @if($bookings->isEmpty())
    <div class="text-center py-16">
        <div class="text-4xl mb-3">📅</div>
        <p class="text-gray-500 font-medium">No bookings found</p>
        <p class="text-gray-400 text-sm mt-1">Try a different date or class filter.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Card No.</th>
                    <th class="table-head">Plan Tier</th>
                    <th class="table-head">Class</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Booked At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $b)
                @php $card = $b->user->employees->first()?->membership_card_no ?? '—'; @endphp
                <tr class="table-row">
                    <td class="table-cell font-mono text-gray-700 text-sm">{{ $card }}</td>
                    <td class="table-cell">
                        @php $tier = $b->user->employees->first()?->subscription?->plan?->tier ?? null; @endphp
                        @if($tier === 'gold') <span class="badge-gold">Gold</span>
                        @elseif($tier === 'silver') <span class="badge-silver">Silver</span>
                        @elseif($tier) <span class="badge-bronze">Bronze</span>
                        @else <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    <td class="table-cell text-gray-700 text-sm">{{ $b->gymClass->name }}</td>
                    <td class="table-cell">
                        @if($b->status === 'confirmed') <span class="badge-green">Confirmed</span>
                        @elseif($b->status === 'waitlisted') <span class="badge-amber">Waitlisted</span>
                        @else <span class="badge-gray">Cancelled</span>
                        @endif
                    </td>
                    <td class="table-cell text-gray-400 text-sm">{{ $b->booked_at->format('H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

</x-layouts.gym-portal>
