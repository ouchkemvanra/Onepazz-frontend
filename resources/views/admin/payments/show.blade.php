<x-layouts.admin title="Payment #{{ $payment->id }}">

<a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Payments
</a>

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Payment #{{ $payment->id }}</h1>
    <p class="text-sm text-gray-400 mt-1">Review payment details and confirm or reject</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Payment details --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Payment Information</h3>
            <dl class="space-y-3 text-sm">
                @foreach([
                    ['Payment ID', '#'.$payment->id, 'font-mono'],
                    ['Invoice', '#'.$payment->invoice->invoice_number, 'font-mono'],
                    ['Employer', $payment->employer->company_name, 'font-semibold'],
                    ['Amount (USD)', '$'.number_format($payment->amount_usd, 2), 'font-bold'],
                    ['Amount (KHR)', number_format($payment->amount_khr).' ៛', 'font-semibold'],
                    ['Transfer Date', $payment->transfer_date?->format('d M Y') ?? '—', ''],
                    ['Bank Name', $payment->bank_name ?? '—', ''],
                    ['Reference', $payment->transfer_reference ?? '—', 'font-mono text-xs'],
                ] as [$label, $value, $cls])
                <div class="flex justify-between py-1 border-b border-gray-50 last:border-0">
                    <span class="text-gray-400">{{ $label }}</span>
                    <span class="text-gray-800 {{ $cls }}">{{ $value }}</span>
                </div>
                @endforeach
                <div class="flex justify-between py-1">
                    <span class="text-gray-400">Status</span>
                    <span>
                        @if($payment->status === 'pending') <span class="badge-amber">Pending</span>
                        @elseif($payment->status === 'confirmed') <span class="badge-green">Confirmed</span>
                        @elseif($payment->status === 'rejected') <span class="badge-red">Rejected</span>
                        @endif
                    </span>
                </div>
            </dl>
        </div>

        @if($payment->receiptUrl())
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-4">Payment Receipt</h3>
            <a href="{{ $payment->receiptUrl() }}" target="_blank" class="block">
                <img src="{{ $payment->receiptUrl() }}" alt="Receipt" class="w-full border border-gray-100 rounded-xl">
            </a>
            <a href="{{ $payment->receiptUrl() }}" target="_blank" class="text-sm text-brand-600 hover:text-brand-700 mt-3 inline-block font-medium">View Full Size →</a>
        </div>
        @endif

        @if($payment->notes || $payment->rejection_reason)
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-3">Notes</h3>
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
        <div class="card p-6" x-data="{ confirmModal: false, rejectModal: false }">
            <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
            <button @click="confirmModal = true" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-xl hover:bg-green-700 transition font-semibold text-sm mb-3">
                ✓ Confirm Payment
            </button>
            <button @click="rejectModal = true" class="w-full bg-red-600 text-white px-4 py-2.5 rounded-xl hover:bg-red-700 transition font-semibold text-sm">
                ✗ Reject Payment
            </button>

            <div x-show="confirmModal" x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm" @click.self="confirmModal = false">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-card-hover">
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Confirm Payment</h3>
                    <p class="text-sm text-gray-500 mb-4">This will mark the payment as confirmed and activate the employer's subscription.</p>
                    <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                        @csrf
                        <label class="label">Notes (optional)</label>
                        <textarea name="notes" rows="3" class="input mb-4" placeholder="Add any notes..."></textarea>
                        <div class="flex gap-2">
                            <button type="button" @click="confirmModal = false" class="btn-secondary flex-1 justify-center">Cancel</button>
                            <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 text-sm font-semibold">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>

            <div x-show="rejectModal" x-cloak class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 backdrop-blur-sm" @click.self="rejectModal = false">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 shadow-card-hover">
                    <h3 class="font-bold text-gray-900 text-lg mb-2">Reject Payment</h3>
                    <p class="text-sm text-gray-500 mb-4">Please provide a reason for rejecting this payment.</p>
                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}">
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
        @else
        <div class="card p-6">
            <h3 class="font-bold text-gray-900 mb-3">Status</h3>
            <p class="text-sm text-gray-600">
                This payment has been <strong>{{ $payment->status }}</strong>
                @if($payment->confirmedBy)by {{ $payment->confirmedBy->full_name }}@endif
            </p>
        </div>
        @endif
    </div>
</div>

</x-layouts.admin>
