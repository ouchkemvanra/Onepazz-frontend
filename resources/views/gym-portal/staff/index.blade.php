<x-layouts.gym-portal title="Staff">

<div class="flex items-center justify-between mb-6" x-data="{ showInvite: {{ isset($showForm) && $showForm ? 'true' : 'false' }} }">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Staff</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $staff->count() }} team member{{ $staff->count() !== 1 ? 's' : '' }}</p>
    </div>
    <button @click="showInvite = !showInvite" class="btn-primary text-sm">
        + Invite Staff
    </button>
</div>

<div x-data="{ showInvite: {{ isset($showForm) && $showForm ? 'true' : 'false' }} }">

{{-- Invite form --}}
<div x-show="showInvite" x-cloak class="card border-brand-200 p-6 mb-6">
    <h3 class="font-bold text-gray-900 mb-4">Invite New Staff Member</h3>
    <form method="POST" action="{{ route('gym-portal.staff.store') }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <label class="label">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input">
            </div>
            <div>
                <label class="label">Role</label>
                <select name="role" class="input">
                    @foreach(['cashier','receptionist','trainer','manager'] as $r)
                    <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex gap-3 mt-4">
            <button type="submit" class="btn-primary text-sm">Send Invite</button>
            <button type="button" @click="showInvite = false" class="btn-secondary text-sm">Cancel</button>
        </div>
    </form>
    <p class="text-xs text-gray-400 mt-3">If the email has no OnePazz account yet, one will be created with a temporary password.</p>
</div>

{{-- Staff table --}}
<div class="card overflow-hidden mb-6">
    @if($staff->isEmpty())
    <div class="text-center py-16">
        <div class="text-4xl mb-3">👥</div>
        <p class="text-gray-500 font-medium">No staff members yet</p>
        <p class="text-gray-400 text-sm mt-1">Invite your first team member to get started.</p>
        <button @click="showInvite = true" class="btn-primary text-sm mt-4">+ Invite Staff</button>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Name</th>
                    <th class="table-head">Email</th>
                    <th class="table-head">Role</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Joined</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $member)
                <tr class="table-row" x-data="{ editRole: false }">
                    <td class="table-cell font-semibold text-gray-800 text-sm">{{ $member->user->full_name }}</td>
                    <td class="table-cell text-gray-500 text-sm">{{ $member->user->email }}</td>
                    <td class="table-cell">
                        <span x-show="!editRole">
                            @php $roleColors = ['cashier'=>'badge-brand','receptionist'=>'badge','trainer'=>'badge-amber','manager'=>'badge-green']; @endphp
                            <span class="{{ $roleColors[$member->role] ?? 'badge-gray' }} text-xs">{{ ucfirst($member->role) }}</span>
                        </span>
                        <form x-show="editRole" method="POST" action="{{ route('gym-portal.staff.role', $member) }}" class="flex gap-2 items-center">
                            @csrf @method('PATCH')
                            <select name="role" class="input text-xs py-1">
                                @foreach(['cashier','receptionist','trainer','manager'] as $r)
                                <option value="{{ $r }}" {{ $member->role === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-primary text-xs py-1 px-2">Save</button>
                            <button type="button" @click="editRole = false" class="text-xs text-gray-400 hover:text-gray-600">Cancel</button>
                        </form>
                    </td>
                    <td class="table-cell">
                        @if($member->is_active)
                            <span class="badge-green text-xs">Active</span>
                        @else
                            <span class="badge-gray text-xs">Inactive</span>
                        @endif
                    </td>
                    <td class="table-cell text-gray-400 text-xs">{{ $member->joined_at?->format('d M Y') ?? '—' }}</td>
                    <td class="table-cell">
                        <div class="flex gap-2">
                            <button @click="editRole = !editRole" class="btn-ghost text-xs py-1 px-2">Change Role</button>
                            <form method="POST" action="{{ route('gym-portal.staff.remove', $member) }}"
                                  onsubmit="return confirm('Remove this staff member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger text-xs py-1 px-2">Remove</button>
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

{{-- Check-in screen promo --}}
<div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl p-5 flex items-center justify-between">
    <div>
        <p class="font-bold text-white">Real-time Check-in Screen</p>
        <p class="text-sm text-gray-400 mt-0.5">Live view for your front desk</p>
    </div>
    <a href="{{ route('gym-portal.checkin-screen') }}" target="_blank" class="btn-primary text-sm whitespace-nowrap">
        Open Screen →
    </a>
</div>
</div>

</x-layouts.gym-portal>
