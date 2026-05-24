<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment #{{ $payment->id }} — Admin — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit Admin
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm font-medium text-teal-600">Payments</a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Gym Applications</a>
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
    <a href="{{ route('admin.payments.index') }}" class="text-sm text-teal-600 hover:text-teal-700 mb-6 inline-block">← Back to Payments</a>

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Payment #{{ $payment->id }}</h1>
        <p class="text-sm text-gray-400 mt-1">Review payment details and confirm or reject</p>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Payment Details --}}
        <div class="col-span-2 space-y-6">

            {{-- Payment Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Payment Information</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Payment ID</span>
                        <span class="font-mono text-gray-800">#{{ $payment->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Invoice</span>
                        <span class="font-mono text-gray-800">#{{ $payment->invoice->invoice_number }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Employer</span>
                        <span class="font-semibold text-gray-800">{{ $payment->employer->company_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Amount (USD)</span>
                        <span class="font-bold text-gray-800">${{ number_format($payment->amount_usd, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Amount (KHR)</span>
                        <span class="font-semibold text-gray-800">{{ number_format($payment->amount_khr) }} ៛</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Transfer Date</span>
                        <span class="text-gray-800">{{ $payment->transfer_date?->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Bank Name</span>
                        <span class="text-gray-800">{{ $payment->bank_name ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Reference</span>
                        <span class="font-mono text-xs text-gray-800">{{ $payment->transfer_reference ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status</span>
                        <span>
                            @if($payment->status === 'pending')
                                <span class="bg-orange-50 text-orange-600 border border-orange-200 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
                            @elseif($payment->status === 'confirmed')
                                <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-medium px-2 py-1 rounded-full">Confirmed</span>
                            @elseif($payment->status === 'rejected')
                                <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-medium px-2 py-1 rounded-full">Rejected</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Receipt --}}
            @if($payment->receiptUrl())
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-4">Payment Receipt</h3>
                <a href="{{ $payment->receiptUrl() }}" target="_blank" class="block">
                    <img src="{{ $payment->receiptUrl() }}" alt="Receipt" class="w-full border border-gray-200 rounded-lg">
                </a>
                <a href="{{ $payment->receiptUrl() }}" target="_blank" class="text-sm text-teal-600 hover:text-teal-700 mt-3 inline-block">View Full Size →</a>
            </div>
            @endif

            {{-- Notes --}}
            @if($payment->notes || $payment->rejection_reason)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-3">Notes</h3>
                <p class="text-sm text-gray-600">{{ $payment->notes ?? $payment->rejection_reason }}</p>
                @if($payment->confirmedBy)
                <p class="text-xs text-gray-400 mt-2">By {{ $payment->confirmedBy->full_name }} on {{ $payment->confirmed_at?->format('d M Y H:i') }}</p>
                @endif
            </div>
            @endif

        </div>

        {{-- Actions --}}
        <div>
            @if($payment->status === 'pending')
            <div class="bg-white rounded-xl border border-gray-200 p-6" x-data="{ confirmModal: false, rejectModal: false }">
                <h3 class="font-semibold mb-4">Actions</h3>

                <button @click="confirmModal = true" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition mb-3">
                    ✓ Confirm Payment
                </button>

                <button @click="rejectModal = true" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                    ✗ Reject Payment
                </button>

                {{-- Confirm Modal --}}
                <div x-show="confirmModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="confirmModal = false">
                    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                        <h3 class="font-semibold text-lg mb-3">Confirm Payment</h3>
                        <p class="text-sm text-gray-600 mb-4">This will mark the payment as confirmed and activate the employer's subscription.</p>
                        <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optional)</label>
                            <textarea name="notes" rows="3" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm mb-4" placeholder="Add any notes..."></textarea>
                            <div class="flex gap-2">
                                <button type="button" @click="confirmModal = false" class="flex-1 border border-gray-200 px-4 py-2 rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Confirm</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Reject Modal --}}
                <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @click.self="rejectModal = false">
                    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                        <h3 class="font-semibold text-lg mb-3">Reject Payment</h3>
                        <p class="text-sm text-gray-600 mb-4">Please provide a reason for rejecting this payment.</p>
                        <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
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
            @else
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold mb-3">Status</h3>
                <p class="text-sm text-gray-600">
                    This payment has been
                    <strong>{{ $payment->status }}</strong>
                    @if($payment->confirmedBy)
                        by {{ $payment->confirmedBy->full_name }}
                    @endif
                </p>
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
