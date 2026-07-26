<x-layouts.admin title="Gym Invitations">

<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Gym Invitations</h1>
        <p class="text-sm text-gray-400 mt-1">Track sent invitations to prospective partners</p>
    </div>
    <a href="{{ route('admin.gyms.invite') }}" class="btn-primary text-sm">+ Send Invitation</a>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6 text-sm">{{ session('success') }}</div>
@endif

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Studio / Contact</th>
                    <th class="table-head">Sent</th>
                    <th class="table-head">Expires</th>
                    <th class="table-head">Invited By</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invitations as $inv)
                @php
                    $expired = $inv->isInviteExpired();
                    $accepted = in_array($inv->status, ['under_review','approved']);
                @endphp
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="font-semibold text-gray-800 text-sm">{{ $inv->studio_name }}</div>
                        <div class="text-xs text-gray-400">{{ $inv->contact_name }} · {{ $inv->contact_email }}</div>
                    </td>
                    <td class="table-cell text-xs text-gray-500">{{ $inv->invite_sent_at?->format('d M Y') ?? '—' }}</td>
                    <td class="table-cell text-xs {{ $expired && !$accepted ? 'text-red-500 font-medium' : 'text-gray-500' }}">
                        {{ $inv->invite_expires_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="table-cell text-xs text-gray-500">{{ $inv->invitedBy?->full_name ?? '—' }}</td>
                    <td class="table-cell">
                        @if($accepted) <span class="badge-green">Accepted</span>
                        @elseif($expired) <span class="badge-red">Expired</span>
                        @else <span class="badge-amber">Pending</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        <div class="flex gap-2">
                            @if(!$accepted)
                            <form method="POST" action="{{ route('admin.gyms.invitations.resend', $inv) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs border border-gray-200 px-2.5 py-1 rounded-lg hover:bg-gray-50 text-gray-600">Resend</button>
                            </form>
                            <form method="POST" action="{{ route('admin.gyms.invitations.cancel', $inv) }}" class="inline"
                                onsubmit="return confirm('Cancel this invitation?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs border border-red-200 text-red-600 px-2.5 py-1 rounded-lg hover:bg-red-50">Cancel</button>
                            </form>
                            @endif
                            @if($accepted)
                            <a href="{{ route('admin.gym-applications.show', $inv) }}" class="text-brand-600 hover:text-brand-700 font-semibold text-sm">Review →</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="text-4xl mb-3">✉️</div>
                        <p class="text-gray-500 font-medium">No invitations sent yet</p>
                        <a href="{{ route('admin.gyms.invite') }}" class="text-brand-600 hover:text-brand-700 text-sm font-medium mt-2 inline-block">Send your first invitation →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invitations->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $invitations->links() }}</div>
    @endif
</div>

</x-layouts.admin>
