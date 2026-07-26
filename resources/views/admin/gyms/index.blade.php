<x-layouts.admin title="Gyms">

<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Gym Management</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $gyms->total() }} partners</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.gyms.invite') }}" class="btn-secondary text-sm">Send Invitation</a>
        <a href="{{ route('admin.gyms.create') }}" class="btn-primary text-sm">+ Add New Gym</a>
    </div>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Name</th>
                    <th class="table-head">Tier</th>
                    <th class="table-head">Status</th>
                    <th class="table-head text-right">Monthly Fee</th>
                    <th class="table-head text-right">Rev. Share %</th>
                    <th class="table-head">Partner Since</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gyms as $gym)
                <tr class="table-row">
                    <td class="table-cell">
                        <p class="font-semibold text-gray-800 text-sm">{{ $gym->name }}</p>
                        <p class="text-xs text-gray-400">{{ $gym->city }}</p>
                    </td>
                    <td class="table-cell">
                        @if($gym->tier === 'gold') <span class="badge-gold">Gold</span>
                        @elseif($gym->tier === 'silver') <span class="badge-silver">Silver</span>
                        @else <span class="badge-bronze">Bronze</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($gym->status === 'active') <span class="badge-green">Active</span>
                        @elseif($gym->status === 'suspended') <span class="badge-red">Suspended</span>
                        @else <span class="badge-gray">{{ ucfirst($gym->status) }}</span>
                        @endif
                    </td>
                    <td class="table-cell text-right font-mono font-bold text-gray-700">${{ number_format($gym->monthly_fee_usd, 2) }}</td>
                    <td class="table-cell text-right text-gray-700 font-semibold">{{ $gym->revenue_share_pct }}%</td>
                    <td class="table-cell text-gray-400 text-xs">{{ $gym->partner_since?->format('d M Y') ?? '—' }}</td>
                    <td class="table-cell">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.gyms.edit', $gym) }}" class="btn-ghost text-xs py-1 px-2">Edit</a>
                            @if($gym->status === 'active')
                            <form method="POST" action="{{ route('admin.gyms.suspend', $gym) }}">@csrf
                                <button class="btn-danger text-xs py-1 px-2">Suspend</button>
                            </form>
                            @elseif($gym->status === 'suspended')
                            <form method="POST" action="{{ route('admin.gyms.activate', $gym) }}">@csrf
                                <button class="text-xs border border-green-200 text-green-600 px-2 py-1 rounded-lg hover:bg-green-50">Activate</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $gyms->links() }}</div>
</div>

</x-layouts.admin>
