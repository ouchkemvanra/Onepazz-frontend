<x-layouts.dashboard title="{{ __('profile.profile') }}">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('profile.profile') }}</h1>
    <p class="text-sm text-gray-400 mt-1">{{ __('profile.personal_info') }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left Column --}}
    <div class="space-y-5">

        <div class="card p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 rounded-2xl bg-brand-500 text-white text-2xl flex items-center justify-center font-semibold shrink-0">
                    {{ strtoupper(substr($user->full_name, 0, 2)) }}
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">{{ $user->full_name }}</h3>
                    @if($user->full_name_kh)
                    <p class="text-sm text-gray-400">{{ $user->full_name_kh }}</p>
                    @endif
                </div>
            </div>

            <dl class="space-y-2 text-sm">
                @foreach([
                    ['Email', $user->email],
                    $user->phone ? ['Phone', $user->phone] : null,
                    $user->date_of_birth ? ['Date of Birth', $user->date_of_birth->format('d M Y')] : null,
                    $user->gender ? ['Gender', ucfirst($user->gender)] : null,
                    ['Role', ucfirst(str_replace('_', ' ', $user->role))],
                ] as $row)
                @if($row)
                <div class="flex justify-between py-1 border-b border-gray-50 last:border-0">
                    <span class="text-gray-400">{{ $row[0] }}</span>
                    <span class="text-gray-800">{{ $row[1] }}</span>
                </div>
                @endif
                @endforeach
            </dl>
        </div>

        @if($employee)
        <div class="bg-gradient-to-br from-brand-500 via-brand-600 to-brand-700 rounded-2xl p-6 text-white shadow-lg">
            <p class="text-brand-100 text-xs uppercase tracking-wide mb-1">Membership Card</p>
            <p class="text-2xl font-bold font-mono mt-1 mb-4">{{ $employee->membership_card_no }}</p>
            <dl class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-brand-100">Company</span>
                    <span class="font-semibold">{{ $employee->employer->company_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-brand-100">Plan</span>
                    <span class="font-semibold">{{ $employee->subscription?->plan?->name ?? 'No Plan' }}</span>
                </div>
                @if($employee->department)
                <div class="flex justify-between">
                    <span class="text-brand-100">Department</span>
                    <span class="font-semibold">{{ $employee->department }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-brand-100">Status</span>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $employee->status === 'active' ? 'bg-green-500' : 'bg-red-500' }} text-white">
                        {{ ucfirst($employee->status) }}
                    </span>
                </div>
            </dl>
        </div>
        @else
        <div class="card p-6 text-center">
            <p class="text-sm text-gray-400">No membership card assigned</p>
        </div>
        @endif

    </div>

    {{-- Right Column --}}
    <div class="lg:col-span-2 space-y-5">

        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">{{ __('profile.checkin_history') }}</h3>
            @forelse($checkins as $checkin)
            <div class="flex items-center justify-between border-b border-gray-50 pb-3 mb-3 last:border-0 last:mb-0 last:pb-0">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-brand-50 rounded-xl flex items-center justify-center text-brand-600 text-lg">🏋️</div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">{{ $checkin->gym->name }}</p>
                        <p class="text-xs text-gray-400">
                            {{ $checkin->checked_in_at->format('d M Y, H:i') }}
                            @if($checkin->gymClass) · {{ $checkin->gymClass->name }} @endif
                        </p>
                    </div>
                </div>
                @if($checkin->duration_minutes)
                <span class="text-sm text-gray-500">{{ $checkin->duration_minutes }} min</span>
                @endif
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-8">No check-ins yet. Visit a gym to get started!</p>
            @endforelse
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Saved Gyms</h3>
            @if($savedGyms->count() > 0)
            <div class="grid grid-cols-2 gap-4">
                @foreach($savedGyms as $gym)
                <a href="{{ route('gyms.show', $gym) }}" class="border border-gray-200 rounded-2xl p-4 hover:border-brand-300 hover:bg-brand-50/30 transition">
                    <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $gym->name }}</h4>
                    @if($gym->name_kh)
                    <p class="text-xs text-gray-400 mb-2">{{ $gym->name_kh }}</p>
                    @endif
                    <p class="text-xs text-gray-500">{{ $gym->district }}, {{ $gym->city }}</p>
                    <p class="text-xs text-gray-400 mt-2">Saved {{ $gym->pivot->saved_at ? \Carbon\Carbon::parse($gym->pivot->saved_at)->diffForHumans() : '' }}</p>
                </a>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400 text-center py-8">No saved gyms yet. <a href="{{ route('gyms.index') }}" class="text-brand-600 hover:text-brand-700 font-medium">Browse gyms →</a></p>
            @endif
        </div>

    </div>

</div>

</x-layouts.dashboard>
