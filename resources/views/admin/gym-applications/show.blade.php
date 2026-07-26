<x-layouts.admin title="Application #{{ $application->id }}">

<a href="{{ route('admin.gym-applications.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Applications
</a>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Application #{{ $application->id }}</h1>
    <p class="text-sm text-gray-400 mt-1">Review gym partner application details</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Details --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Studio Information</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Studio Name</span>
                    <span class="font-bold text-gray-800">{{ $application->studio_name }}</span>
                </div>
                @if($application->studio_name_kh)
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Studio Name (KH)</span>
                    <span class="font-semibold text-gray-800">{{ $application->studio_name_kh }}</span>
                </div>
                @endif
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-gray-400">Activity Types</span>
                    <span class="text-gray-800">{{ $application->activity_types ? implode(', ', $application->activity_types) : '—' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-400">Website</span>
                    @if($application->website)
                    <a href="{{ $application->website }}" target="_blank" class="text-brand-600 hover:underline">{{ $application->website }}</a>
                    @else
                    <span class="text-gray-800">—</span>
                    @endif
                </div>
            </dl>
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Contact Information</h3>
            <dl class="space-y-3 text-sm">
                @foreach([
                    ['Contact Name', $application->contact_name],
                    ['Email', $application->contact_email],
                    ['Phone', $application->contact_phone],
                ] as [$label, $value])
                <div class="flex justify-between py-1 border-b border-gray-50 last:border-0">
                    <span class="text-gray-400">{{ $label }}</span>
                    <span class="text-gray-800">{{ $value }}</span>
                </div>
                @endforeach
            </dl>
        </div>

        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Location</h3>
            <dl class="space-y-3 text-sm">
                @foreach([['Address', $application->address], ['District', $application->district], ['City', $application->city]] as [$label, $value])
                <div class="flex justify-between py-1 border-b border-gray-50 last:border-0">
                    <span class="text-gray-400">{{ $label }}</span>
                    <span class="text-gray-800">{{ $value }}</span>
                </div>
                @endforeach
            </dl>
        </div>

        @if($application->description)
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-3">Description</h3>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $application->description }}</p>
        </div>
        @endif

        @if($application->notes || $application->rejection_reason)
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-3">Review Notes</h3>
            <p class="text-sm text-gray-600">{{ $application->notes ?? $application->rejection_reason }}</p>
            @if($application->reviewer)
            <p class="text-xs text-gray-400 mt-2">By {{ $application->reviewer->full_name }} on {{ $application->reviewed_at?->format('d M Y H:i') }}</p>
            @endif
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="space-y-4">
        <div class="card p-5">
            <h3 class="font-bold text-gray-900 mb-3">Status</h3>
            @if($application->status === 'pending') <span class="badge-amber">Pending Review</span>
            @elseif($application->status === 'approved') <span class="badge-green">Approved</span>
            @else <span class="badge-red">Rejected</span>
            @endif
            <p class="text-xs text-gray-400 mt-3">Applied {{ $application->created_at->format('d M Y') }}</p>
        </div>

        @if($application->status === 'pending')
        <div class="card p-5" x-data="{ approveModal: false, rejectModal: false }">
            <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
            <button @click="approveModal = true" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-xl hover:bg-green-700 transition font-semibold text-sm mb-3">
                ✓ Approve Application
            </button>
            <button @click="rejectModal = true" class="w-full bg-red-600 text-white px-4 py-2.5 rounded-xl hover:bg-red-700 transition font-semibold text-sm">
                ✗ Reject Application
            </button>

            <div x-show="approveModal" x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm overflow-y-auto" @click.self="approveModal = false">
                <div class="bg-white rounded-2xl p-6 max-w-lg w-full mx-4 shadow-card-hover my-8" x-data="{ createAccount: true }">
                    <h3 class="font-bold text-gray-900 text-lg mb-1">Approve Gym Application</h3>
                    <p class="text-sm text-gray-500 mb-4">This will create the gym in the system.</p>
                    <form method="POST" action="{{ route('admin.gym-applications.approve', $application) }}">
                        @csrf
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="label">Tier *</label>
                                <select name="tier" required class="input">
                                    @foreach(['bronze','silver','gold'] as $t)
                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="label">Partner Since *</label>
                                <input type="date" name="partner_since" value="{{ today()->toDateString() }}" required class="input">
                            </div>
                        </div>
                        <label class="flex items-center gap-2 mb-3 cursor-pointer text-sm text-gray-700">
                            <input type="checkbox" name="create_gym_admin" value="1" x-model="createAccount" checked class="rounded border-gray-300 accent-brand-500">
                            Create gym admin account
                        </label>
                        <div x-show="createAccount" class="mb-3">
                            <label class="label">Admin Email</label>
                            <input type="email" name="admin_user_email" value="{{ $application->contact_email }}" class="input">
                            <p class="text-xs text-gray-400 mt-1">A temporary password will be generated and emailed.</p>
                        </div>
                        <div class="mb-4">
                            <label class="label">Notes (optional)</label>
                            <textarea name="notes" rows="2" class="input" placeholder="Internal notes..."></textarea>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="approveModal = false" class="btn-secondary flex-1 justify-center text-sm">Cancel</button>
                            <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 text-sm font-semibold">Approve & Create Gym</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm" @click.self="rejectModal = false">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-card-hover">
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Reject Application</h3>
                    <p class="text-sm text-gray-500 mb-4">Please provide a reason for rejecting this application.</p>
                    <form method="POST" action="{{ route('admin.gym-applications.reject', $application) }}">
                        @csrf
                        <label class="label">Rejection Reason *</label>
                        <textarea name="rejection_reason" rows="3" required class="input mb-4" placeholder="Reason for rejection..."></textarea>
                        <div class="flex gap-2">
                            <button type="button" @click="rejectModal = false" class="btn-secondary flex-1 justify-center">Cancel</button>
                            <button type="submit" class="btn-danger flex-1 justify-center">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

</x-layouts.admin>
