<x-layouts.admin title="Employers">

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Employer Management</h1>
        <p class="text-sm text-gray-400 mt-1">Manage corporate clients and registrations</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.employers.invite') }}" class="btn-secondary text-sm">Send Invitation</a>
        <a href="{{ route('admin.employers.create') }}" class="btn-primary text-sm">+ Add Employer</a>
    </div>
</div>

{{-- Tabs --}}
@php $tab = request('tab', 'active'); @endphp
<div class="flex gap-1 mb-6 border-b border-gray-200">
    @foreach(['active'=>'Active Employers','pending'=>'Pending Registrations','invitations'=>'Invitations'] as $key => $label)
    <a href="{{ route('admin.employers.index', ['tab'=>$key]) }}"
        class="px-4 py-2.5 text-sm font-medium border-b-2 -mb-px transition-all
        {{ $tab === $key ? 'border-brand-500 text-brand-600' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
        {{ $label }}
        @if($key === 'pending' && $pending->total() > 0)
        <span class="ml-1.5 badge-amber text-xs">{{ $pending->total() }}</span>
        @endif
    </a>
    @endforeach
</div>

@if($tab === 'active')
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Company</th>
                    <th class="table-head">Contact</th>
                    <th class="table-head">Plan</th>
                    <th class="table-head text-right">Employees</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($active as $emp)
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="font-semibold text-gray-800 text-sm">{{ $emp->company_name }}</div>
                        <div class="text-xs text-gray-400">{{ $emp->city }}</div>
                    </td>
                    <td class="table-cell">
                        <div class="text-sm text-gray-700">{{ $emp->contact_name }}</div>
                        <div class="text-xs text-gray-400">{{ $emp->contact_email }}</div>
                    </td>
                    <td class="table-cell text-sm text-gray-600">{{ $emp->activeSubscription?->plan?->name ?? '—' }}</td>
                    <td class="table-cell text-right font-semibold text-gray-700">{{ $emp->activeSubscription?->employee_count ?? '—' }}</td>
                    <td class="table-cell">
                        @if($emp->status === 'active')
                            <span class="badge-green">Active</span>
                        @else
                            <span class="badge-red">{{ ucfirst($emp->status) }}</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.employers.edit', $emp) }}" class="btn-ghost text-xs py-1 px-2">Edit</a>
                            @if($emp->status === 'active')
                            <form method="POST" action="{{ route('admin.employers.suspend', $emp) }}">@csrf @method('PATCH')
                                <button class="btn-danger text-xs py-1 px-2">Suspend</button>
                            </form>
                            @elseif($emp->status === 'suspended')
                            <form method="POST" action="{{ route('admin.employers.activate', $emp) }}">@csrf @method('PATCH')
                                <button class="text-xs border border-green-200 text-green-600 px-2 py-1 rounded-lg hover:bg-green-50">Activate</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-16 text-center text-gray-400">No employers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $active->appends(['tab'=>'active'])->links() }}</div>
</div>
@endif

@if($tab === 'pending')
<div class="card overflow-hidden" x-data="{ rejectId: null }">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Company</th>
                    <th class="table-head">Plan / Employees</th>
                    <th class="table-head text-right">Invoice</th>
                    <th class="table-head">Ref Code</th>
                    <th class="table-head">Registered</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pending as $emp)
                @php $inv = $emp->invoices()->latest()->first(); @endphp
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="font-semibold text-gray-800 text-sm">{{ $emp->company_name }}</div>
                        <div class="text-xs text-gray-400">{{ $emp->contact_email }}</div>
                    </td>
                    <td class="table-cell text-xs text-gray-600">
                        {{ $emp->activeSubscription?->plan?->name ?? '—' }}<br>
                        {{ $emp->activeSubscription?->employee_count ?? '—' }} employees
                    </td>
                    <td class="table-cell text-right font-mono font-bold text-gray-700">
                        {{ $inv ? '$'.number_format($inv->total_usd,2) : '—' }}
                    </td>
                    <td class="table-cell font-mono text-xs text-brand-600">{{ $emp->reference_code ?? '—' }}</td>
                    <td class="table-cell text-xs text-gray-400">{{ $emp->created_at->format('d M Y') }}</td>
                    <td class="table-cell">
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.employers.approve', $emp) }}">@csrf
                                <button class="text-xs bg-green-600 text-white px-2 py-1 rounded-lg hover:bg-green-700">Approve</button>
                            </form>
                            <button @click="rejectId = {{ $emp->id }}" class="btn-danger text-xs py-1 px-2">Reject</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-16 text-center text-gray-400">No pending registrations.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $pending->appends(['tab'=>'pending'])->links() }}</div>

    @foreach($pending as $emp)
    <div x-show="rejectId === {{ $emp->id }}" x-cloak
         class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm"
         @click.self="rejectId = null">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-card-hover">
            <h3 class="font-bold text-gray-900 mb-3">Reject Registration</h3>
            <form method="POST" action="{{ route('admin.employers.reject', $emp) }}">
                @csrf
                <textarea name="rejection_reason" rows="3" required placeholder="Reason for rejection..."
                    class="input mb-4"></textarea>
                <div class="flex gap-2">
                    <button type="button" @click="rejectId = null" class="btn-secondary flex-1 justify-center">Cancel</button>
                    <button class="btn-danger flex-1 justify-center">Reject</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($tab === 'invitations')
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Company / Contact</th>
                    <th class="table-head">Plan</th>
                    <th class="table-head">Sent</th>
                    <th class="table-head">Expires</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invitations as $inv)
                @php $expired = $inv->isExpired(); @endphp
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="font-semibold text-gray-800 text-sm">{{ $inv->company_name ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $inv->contact_name }} · {{ $inv->contact_email }}</div>
                    </td>
                    <td class="table-cell text-xs text-gray-600">{{ $inv->suggestedPlan?->name ?? '—' }}</td>
                    <td class="table-cell text-xs text-gray-400">{{ $inv->invite_sent_at->format('d M Y') }}</td>
                    <td class="table-cell text-xs {{ $expired && $inv->status !== 'accepted' ? 'text-red-500 font-medium' : 'text-gray-400' }}">
                        {{ $inv->invite_expires_at->format('d M Y') }}
                    </td>
                    <td class="table-cell">
                        @if($inv->status === 'accepted')
                            <span class="badge-green">Accepted</span>
                        @elseif($expired)
                            <span class="badge-red">Expired</span>
                        @else
                            <span class="badge-amber">Pending</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if($inv->status !== 'accepted')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.employers.invitations.resend', $inv) }}">@csrf
                                <button class="btn-ghost text-xs py-1 px-2">Resend</button>
                            </form>
                            <form method="POST" action="{{ route('admin.employers.invitations.cancel', $inv) }}"
                                  onsubmit="return confirm('Cancel invitation?')">@csrf @method('DELETE')
                                <button class="btn-danger text-xs py-1 px-2">Cancel</button>
                            </form>
                        </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-16 text-center text-gray-400">No invitations sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100">{{ $invitations->appends(['tab'=>'invitations'])->links() }}</div>
</div>
@endif

</x-layouts.admin>
