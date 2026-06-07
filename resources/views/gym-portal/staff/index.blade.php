<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff — {{ $gym->name }} — OnePazz Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-5xl mx-auto px-6 py-8" x-data="{ showInvite: {{ isset($showForm) && $showForm ? 'true' : 'false' }} }">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Staff</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $staff->count() }} team member{{ $staff->count() !== 1 ? 's' : '' }}</p>
        </div>
        <button @click="showInvite = !showInvite" class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">
            + Invite Staff
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    {{-- Invite Form --}}
    <div x-show="showInvite" x-cloak class="bg-white rounded-xl border border-teal-200 p-6 mb-6">
        <h3 class="font-semibold text-gray-800 mb-4">Invite New Staff Member</h3>
        <form method="POST" action="{{ route('gym-portal.staff.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                        @foreach(['cashier','receptionist','trainer','manager'] as $r)
                        <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex gap-3 mt-4">
                <button type="submit" class="bg-teal-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-teal-700 transition">Send Invite</button>
                <button type="button" @click="showInvite = false" class="border border-gray-200 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">Cancel</button>
            </div>
        </form>
        <p class="text-xs text-gray-400 mt-3">If the email doesn't have a OnePazz account yet, one will be created with a temporary password.</p>
    </div>

    {{-- Staff Table --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        @if($staff->isEmpty())
        <p class="text-sm text-gray-400 text-center py-8">No staff members yet. Invite your first team member above.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Name</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Email</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Role</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Status</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Joined</th>
                        <th class="text-left py-3 px-3 text-gray-400 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($staff as $member)
                    <tr class="border-b border-gray-50 hover:bg-gray-50" x-data="{ editRole: false }">
                        <td class="py-3 px-3 font-medium text-gray-800">{{ $member->user->full_name }}</td>
                        <td class="py-3 px-3 text-gray-500">{{ $member->user->email }}</td>
                        <td class="py-3 px-3">
                            <span x-show="!editRole">
                                @php $roleColors = ['cashier'=>'bg-blue-100 text-blue-700','receptionist'=>'bg-purple-100 text-purple-700','trainer'=>'bg-orange-100 text-orange-700','manager'=>'bg-teal-100 text-teal-700']; @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$member->role] ?? 'bg-gray-100 text-gray-600' }}">{{ ucfirst($member->role) }}</span>
                            </span>
                            <form x-show="editRole" method="POST" action="{{ route('gym-portal.staff.role', $member) }}" class="flex gap-2 items-center">
                                @csrf @method('PATCH')
                                <select name="role" class="border border-gray-200 rounded px-2 py-1 text-xs">
                                    @foreach(['cashier','receptionist','trainer','manager'] as $r)
                                    <option value="{{ $r }}" {{ $member->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="text-xs bg-teal-600 text-white px-2 py-1 rounded">Save</button>
                                <button type="button" @click="editRole = false" class="text-xs text-gray-400">Cancel</button>
                            </form>
                        </td>
                        <td class="py-3 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs {{ $member->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $member->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-3 px-3 text-gray-500 text-xs">{{ $member->joined_at?->format('d M Y') ?? '—' }}</td>
                        <td class="py-3 px-3">
                            <div class="flex gap-2">
                                <button @click="editRole = !editRole" class="text-xs border border-gray-200 px-2 py-1 rounded hover:bg-gray-50 transition">Change Role</button>
                                <form method="POST" action="{{ route('gym-portal.staff.remove', $member) }}" onsubmit="return confirm('Remove this staff member?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs border border-red-200 text-red-600 px-2 py-1 rounded hover:bg-red-50 transition">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Checkin Screen Link --}}
    <div class="mt-6 bg-gray-800 rounded-xl p-5 flex items-center justify-between">
        <div>
            <p class="font-semibold text-white">Real-time Check-in Screen</p>
            <p class="text-sm text-gray-400 mt-0.5">Live view with WebSocket updates for your front desk</p>
        </div>
        <a href="{{ route('gym-portal.checkin-screen') }}" target="_blank"
           class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium whitespace-nowrap">
            Open Screen →
        </a>
    </div>
</div>
</body>
</html>
