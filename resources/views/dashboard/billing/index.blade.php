<x-layouts.dashboard title="{{ __('billing.billing') }}">

{{-- Header --}}
<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('billing.billing') }}</h1>
    <p class="text-sm text-gray-400 mt-1">{{ $employer->company_name }} · View invoices and submit bank transfer payments</p>
</div>

{{-- Bank transfer card --}}
<div class="bg-gradient-to-br from-gray-900 via-gray-800 to-brand-900 rounded-2xl p-6 text-white mb-8 max-w-lg shadow-card-hover">
    <p class="text-xs text-white/40 uppercase tracking-widest mb-1">{{ $bankDetails['bank'] }} · Corporate Account</p>
    <p class="text-2xl font-mono font-bold tracking-widest mb-1">{{ $bankDetails['account'] }}</p>
    <p class="text-sm text-white/60 mb-5">{{ $bankDetails['holder'] }}</p>
    <div class="bg-white/10 rounded-xl p-4 text-xs text-white/60 leading-relaxed space-y-1">
        <p>⚠️ Use your <strong class="text-white/80">invoice number</strong> as the transfer reference.</p>
        <p>Confirmation is processed within 1–2 business days.</p>
        <p>Contact <span class="text-white/80">billing@onepazz.com.kh</span> for queries.</p>
    </div>
</div>

{{-- Invoice list --}}
<div class="card overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-gray-900">Invoice History</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">{{ __('billing.invoice_no') }}</th>
                    <th class="table-head">{{ __('billing.period') }}</th>
                    <th class="table-head">{{ __('employee.employees') }}</th>
                    <th class="table-head">{{ $currency === 'khr' ? __('billing.amount_khr') : __('billing.amount_usd') }}</th>
                    <th class="table-head">{{ __('billing.status') }}</th>
                    <th class="table-head">{{ __('billing.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr class="table-row">
                    <td class="table-cell font-mono text-xs text-gray-600">{{ $invoice->invoice_number }}</td>
                    <td class="table-cell text-gray-600 text-sm">{{ $invoice->billing_period_start->format('M Y') }}</td>
                    <td class="table-cell text-gray-600 text-sm">{{ $invoice->employee_count }}</td>
                    <td class="table-cell font-bold text-gray-800">{{ format_currency($invoice->total_usd, 2) }}</td>
                    <td class="table-cell">
                        @if($invoice->status === 'paid')
                            <span class="badge-green">{{ __('billing.confirmed') }}</span>
                        @elseif($invoice->status === 'pending_verification')
                            <span class="badge-amber">{{ __('billing.pending_review') }}</span>
                        @elseif($invoice->status === 'overdue')
                            <span class="badge-red">{{ __('billing.overdue') }}</span>
                        @else
                            <span class="badge-gray">{{ __('billing.unpaid') }}</span>
                        @endif
                    </td>
                    <td class="table-cell">
                        @if(in_array($invoice->status, ['unpaid', 'overdue']))
                            <a href="{{ route('dashboard.billing.pay', $invoice) }}" class="btn-primary text-xs py-1.5 px-3">
                                {{ __('billing.submit_payment') }}
                            </a>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-16 text-center">
                        <div class="text-4xl mb-3">🧾</div>
                        <p class="text-gray-500 font-medium mb-1">No invoices yet</p>
                        <p class="text-gray-400 text-sm">{{ __('billing.no_invoices') }}</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $invoices->links() }}
    </div>
    @endif
</div>

</x-layouts.dashboard>
