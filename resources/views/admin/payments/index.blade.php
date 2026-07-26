<x-layouts.admin title="Payments">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Payment Management</h1>
    <p class="text-sm text-gray-400 mt-1">Review and confirm employer payment submissions</p>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">Payment ID</th>
                    <th class="table-head">Employer</th>
                    <th class="table-head">Amount</th>
                    <th class="table-head">Transfer Date</th>
                    <th class="table-head">Status</th>
                    <th class="table-head">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr class="table-row">
                    <td class="table-cell font-mono text-xs text-gray-400">#{{ $payment->id }}</td>
                    <td class="table-cell">
                        <div class="font-semibold text-gray-800 text-sm">{{ $payment->employer->company_name }}</div>
                        <div class="text-xs text-gray-400">Invoice #{{ $payment->invoice->invoice_number }}</div>
                    </td>
                    <td class="table-cell">
                        <div class="font-bold text-gray-800">${{ number_format($payment->amount_usd, 2) }}</div>
                        <div class="text-xs text-gray-400">{{ number_format($payment->amount_khr) }} ៛</div>
                    </td>
                    <td class="table-cell text-gray-600 text-sm">{{ $payment->transfer_date?->format('d M Y') }}</td>
                    <td class="table-cell">
                        @if($payment->status === 'pending')
                            <span class="badge-amber">Pending Review</span>
                        @elseif($payment->status === 'confirmed')
                            <span class="badge-green">Confirmed</span>
                        @elseif($payment->status === 'rejected')
                            <span class="badge-red">Rejected</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        <a href="{{ route('admin.payments.show', $payment) }}" class="text-brand-600 hover:text-brand-700 font-semibold text-sm">Review →</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="text-4xl mb-3">💳</div>
                        <p class="text-gray-500 font-medium">No payments found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($payments->hasPages())
    <div class="p-4 border-t border-gray-100">{{ $payments->links() }}</div>
    @endif
</div>

</x-layouts.admin>
