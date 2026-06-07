<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Invitations — Admin — OnePazz</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('admin._nav')

<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gym Invitations</h1>
            <p class="text-sm text-gray-400 mt-1">Track sent invitations to prospective partners</p>
        </div>
        <a href="{{ route('admin.gyms.invite') }}"
            class="bg-teal-600 text-white px-5 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">
            + Send Invitation
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Studio / Contact</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Sent</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Expires</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Invited By</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invitations as $inv)
                @php
                    $expired = $inv->isInviteExpired();
                    $accepted = in_array($inv->status, ['under_review','approved']);
                @endphp
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $inv->studio_name }}</div>
                        <div class="text-xs text-gray-400">{{ $inv->contact_name }} · {{ $inv->contact_email }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-500 text-xs">{{ $inv->invite_sent_at?->format('d M Y') ?? '—' }}</td>
                    <td class="px-6 py-4 text-xs {{ $expired && !$accepted ? 'text-red-500' : 'text-gray-500' }}">
                        {{ $inv->invite_expires_at?->format('d M Y') ?? '—' }}
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">{{ $inv->invitedBy?->full_name ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @if($accepted)
                            <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">Accepted</span>
                        @elseif($expired)
                            <span class="bg-red-100 text-red-600 text-xs font-medium px-2 py-1 rounded-full">Expired</span>
                        @else
                            <span class="bg-orange-100 text-orange-600 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            @if(!$accepted)
                            <form method="POST" action="{{ route('admin.gyms.invitations.resend', $inv) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50">Resend</button>
                            </form>
                            <form method="POST" action="{{ route('admin.gyms.invitations.cancel', $inv) }}" class="inline"
                                onsubmit="return confirm('Cancel this invitation?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50">Cancel</button>
                            </form>
                            @endif
                            @if($accepted)
                            <a href="{{ route('admin.gym-applications.show', $inv) }}" class="text-xs text-teal-600 hover:text-teal-700">Review →</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-sm">No invitations sent yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invitations->hasPages())
    <div class="mt-6">{{ $invitations->links() }}</div>
    @endif
</div>
</body>
</html>
