<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employers — Admin — OnePazz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-50">
@include('admin._nav')

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Employer Management</h1>
            <p class="text-sm text-gray-400 mt-1">Manage corporate clients and registrations</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.employers.invite') }}" class="border border-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">Send Invitation</a>
            <a href="{{ route('admin.employers.create') }}" class="bg-teal-600 text-white px-5 py-2 rounded-lg hover:bg-teal-700 text-sm font-medium">+ Add Employer</a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    {{-- Tabs --}}
    @php $tab = request('tab', 'active'); @endphp
    <div class="flex gap-1 mb-6 border-b border-gray-200">
        @foreach(['active'=>'Active Employers','pending'=>'Pending Registrations','invitations'=>'Invitations'] as $key => $label)
        <a href="{{ route('admin.employers.index', ['tab'=>$key]) }}"
            class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition
            {{ $tab === $key ? 'border-teal-600 text-teal-600' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
            {{ $label }}
            @if($key === 'pending' && $pending->total() > 0)
            <span class="ml-1.5 bg-orange-100 text-orange-600 text-xs font-semibold px-1.5 py-0.5 rounded-full">{{ $pending->total() }}</span>
            @endif
        </a>
        @endforeach
    </div>

    {{-- Active Tab --}}
    @if($tab === 'active')
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Company</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Contact</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Plan</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Employees</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($active as $emp)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $emp->company_name }}</div>
                        <div class="text-xs text-gray-400">{{ $emp->city }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-700">{{ $emp->contact_name }}</div>
                        <div class="text-xs text-gray-400">{{ $emp->contact_email }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600 text-xs">{{ $emp->activeSubscription?->plan?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-right text-gray-700">{{ $emp->activeSubscription?->employee_count ?? '—' }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $emp->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                            {{ ucfirst($emp->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('admin.employers.edit', $emp) }}" class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50">Edit</a>
                            @if($emp->status === 'active')
                            <form method="POST" action="{{ route('admin.employers.suspend', $emp) }}">@csrf @method('PATCH')
                                <button class="text-xs border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50">Suspend</button>
                            </form>
                            @elseif($emp->status === 'suspended')
                            <form method="POST" action="{{ route('admin.employers.activate', $emp) }}">@csrf @method('PATCH')
                                <button class="text-xs border border-green-200 text-green-600 px-2 py-1 rounded hover:bg-green-50">Activate</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400">No employers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $active->appends(['tab'=>'active'])->links() }}</div>
    </div>
    @endif

    {{-- Pending Tab --}}
    @if($tab === 'pending')
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden" x-data="{ rejectId: null, reason: '' }">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Company</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Plan / Employees</th>
                    <th class="text-right text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Invoice</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Ref Code</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Registered</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $emp)
                @php $inv = $emp->invoices()->latest()->first(); @endphp
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $emp->company_name }}</div>
                        <div class="text-xs text-gray-400">{{ $emp->contact_email }}</div>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-600">
                        {{ $emp->activeSubscription?->plan?->name ?? '—' }}<br>
                        {{ $emp->activeSubscription?->employee_count ?? '—' }} employees
                    </td>
                    <td class="px-6 py-4 text-right font-mono text-gray-700">
                        {{ $inv ? '$'.number_format($inv->total_usd,2) : '—' }}
                    </td>
                    <td class="px-6 py-4 font-mono text-xs text-teal-700">{{ $emp->reference_code ?? '—' }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $emp->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.employers.approve', $emp) }}">@csrf
                                <button class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Approve</button>
                            </form>
                            <button @click="rejectId = {{ $emp->id }}" class="text-xs border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50">Reject</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400">No pending registrations.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $pending->appends(['tab'=>'pending'])->links() }}</div>

        {{-- Reject Modal --}}
        @foreach($pending as $emp)
        <div x-show="rejectId === {{ $emp->id }}" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="rejectId = null">
            <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                <h3 class="font-semibold mb-3">Reject Registration</h3>
                <form method="POST" action="{{ route('admin.employers.reject', $emp) }}">
                    @csrf
                    <textarea name="rejection_reason" rows="3" required placeholder="Reason for rejection..."
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-4"></textarea>
                    <div class="flex gap-2">
                        <button type="button" @click="rejectId = null" class="flex-1 border border-gray-200 px-4 py-2 rounded-lg text-sm">Cancel</button>
                        <button class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Reject</button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Invitations Tab --}}
    @if($tab === 'invitations')
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Company / Contact</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Plan</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Sent</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Expires</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invitations as $inv)
                @php $expired = $inv->isExpired(); @endphp
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $inv->company_name ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $inv->contact_name }} · {{ $inv->contact_email }}</div>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-600">{{ $inv->suggestedPlan?->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $inv->invite_sent_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-xs {{ $expired && $inv->status !== 'accepted' ? 'text-red-500' : 'text-gray-500' }}">
                        {{ $inv->invite_expires_at->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($inv->status === 'accepted')
                        <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">Accepted</span>
                        @elseif($expired)
                        <span class="bg-red-100 text-red-600 text-xs font-medium px-2 py-1 rounded-full">Expired</span>
                        @else
                        <span class="bg-orange-100 text-orange-600 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($inv->status !== 'accepted')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.employers.invitations.resend', $inv) }}">@csrf
                                <button class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50">Resend</button>
                            </form>
                            <form method="POST" action="{{ route('admin.employers.invitations.cancel', $inv) }}" onsubmit="return confirm('Cancel invitation?')">@csrf @method('DELETE')
                                <button class="text-xs border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50">Cancel</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400">No invitations sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">{{ $invitations->appends(['tab'=>'invitations'])->links() }}</div>
    </div>
    @endif

</div>
</body>
</html>
