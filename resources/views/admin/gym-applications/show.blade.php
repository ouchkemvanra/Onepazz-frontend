<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application #{{ $application->id }} — Admin — OnePazz</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            OnePazz Admin
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payments</a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm font-medium text-teal-600">Gym Applications</a>
            <a href="{{ route('admin.settings') }}" class="text-sm text-gray-500 hover:text-gray-800">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800">Logout</button>
            </form>
            <div class="w-8 h-8 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-semibold">
                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
            </div>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-6 py-8">

    {{-- Back Link --}}
    <a href="{{ route('admin.gym-applications.index') }}" class="text-sm text-teal-600 hover:text-teal-700 mb-6 inline-block">← Back to Applications</a>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Application #{{ $application->id }}</h1>
        <p class="text-sm text-gray-400 mt-1">Review gym partner application details</p>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Application Details --}}
        <div class="col-span-2 space-y-6">

            {{-- Studio Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Studio Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Studio Name</span>
                        <span class="font-semibold text-gray-800">{{ $application->studio_name }}</span>
                    </div>
                    @if($application->studio_name_kh)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Studio Name (KH)</span>
                        <span class="font-semibold text-gray-800">{{ $application->studio_name_kh }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-400">Activity Types</span>
                        <span class="text-gray-800">
                            @if($application->activity_types)
                                {{ implode(', ', $application->activity_types) }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Website</span>
                        <span class="text-gray-800">
                            @if($application->website)
                                <a href="{{ $application->website }}" target="_blank" class="text-teal-600 hover:underline">{{ $application->website }}</a>
                            @else
                                —
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Contact Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Contact Name</span>
                        <span class="text-gray-800">{{ $application->contact_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email</span>
                        <span class="text-gray-800">{{ $application->contact_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Phone</span>
                        <span class="text-gray-800">{{ $application->contact_phone }}</span>
                    </div>
                </div>
            </div>

            {{-- Location Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Location</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Address</span>
                        <span class="text-gray-800">{{ $application->address }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">District</span>
                        <span class="text-gray-800">{{ $application->district }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">City</span>
                        <span class="text-gray-800">{{ $application->city }}</span>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($application->description)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-3">Description</h3>
                <p class="text-sm text-gray-600">{{ $application->description }}</p>
            </div>
            @endif

            {{-- Review Notes --}}
            @if($application->notes || $application->rejection_reason)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-3">Review Notes</h3>
                <p class="text-sm text-gray-600">{{ $application->notes ?? $application->rejection_reason }}</p>
                @if($application->reviewer)
                <p class="text-xs text-gray-400 mt-2">By {{ $application->reviewer->full_name }} on {{ $application->reviewed_at?->format('d M Y H:i') }}</p>
                @endif
            </div>
            @endif

        </div>

        {{-- Actions --}}
        <div>
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                <h3 class="font-semibold mb-3">Status</h3>
                @if($application->status === 'pending')
                    <span class="bg-orange-50 text-orange-600 border border-orange-200 text-xs font-medium px-3 py-1.5 rounded-full">Pending Review</span>
                @elseif($application->status === 'approved')
                    <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-medium px-3 py-1.5 rounded-full">Approved</span>
                @elseif($application->status === 'rejected')
                    <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-medium px-3 py-1.5 rounded-full">Rejected</span>
                @endif
                <p class="text-xs text-gray-400 mt-3">Applied {{ $application->created_at->format('d M Y') }}</p>
            </div>

            @if($application->status === 'pending')
            <div class="bg-white rounded-xl border border-gray-200 p-6" x-data="{ approveModal: false, rejectModal: false }">
                <h3 class="font-semibold mb-4">Actions</h3>

                <button @click="approveModal = true" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition mb-3">
                    ✓ Approve Application
                </button>

                <button @click="rejectModal = true" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    ✗ Reject Application
                </button>

                {{-- Approve Modal --}}
                <div x-show="approveModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="approveModal = false">
                    <div class="bg-white rounded-xl p-6 max-w-lg w-full mx-4 max-h-screen overflow-y-auto" x-data="{ createAccount: true }">
                        <h3 class="font-semibold text-lg mb-1">Approve Gym Application</h3>
                        <p class="text-sm text-gray-500 mb-4">This will create the gym in the system.</p>
                        <form method="POST" action="{{ route('admin.gym-applications.approve', $application) }}">
                            @csrf
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tier <span class="text-red-500">*</span></label>
                                    <select name="tier" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                        @foreach(['bronze','silver','gold'] as $t)
                                        <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Partner Since <span class="text-red-500">*</span></label>
                                    <input type="date" name="partner_since" value="{{ today()->toDateString() }}" required
                                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                </div>
                            </div>

                            <label class="flex items-center gap-2 mb-3 cursor-pointer">
                                <input type="checkbox" name="create_gym_admin" value="1" x-model="createAccount" checked
                                    class="rounded border-gray-300 text-teal-600">
                                <span class="text-sm text-gray-700">Create gym admin account</span>
                            </label>

                            <div x-show="createAccount" class="mb-3">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Admin Email</label>
                                <input type="email" name="admin_user_email" value="{{ $application->contact_email }}"
                                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm">
                                <p class="text-xs text-gray-400 mt-1">A temporary password will be generated and emailed.</p>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Notes (optional)</label>
                                <textarea name="notes" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" placeholder="Internal notes..."></textarea>
                            </div>

                            <div class="flex gap-2">
                                <button type="button" @click="approveModal = false" class="flex-1 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">Cancel</button>
                                <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">Approve & Create Gym</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="rejectModal = false">
                    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                        <h3 class="font-semibold text-lg mb-3">Reject Application</h3>
                        <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejecting this application.</p>
                        <form method="POST" action="{{ route('admin.gym-applications.reject', $application) }}">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason *</label>
                            <textarea name="rejection_reason" rows="3" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-4" placeholder="Reason for rejection..."></textarea>
                            <div class="flex gap-2">
                                <button type="button" @click="rejectModal = false" class="flex-1 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="submit" class="flex-1 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">Reject</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

    </div>

</div>

<style>
    [x-cloak] { display: none !important; }
</style>

</body>
</html>
