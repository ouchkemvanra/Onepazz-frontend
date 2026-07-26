<x-layouts.dashboard title="{{ __('billing.billing') }}">

<a href="{{ route('dashboard.billing.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← {{ __('billing.billing') }}
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ __('billing.submit') }}</h1>
    <p class="text-sm text-gray-400 mt-1">Invoice {{ $invoice->invoice_number }}</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl space-y-5">

    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-4">Invoice Summary</h3>
        <dl class="space-y-3 text-sm">
            @foreach([
                [__('billing.invoice_number'), $invoice->invoice_number, 'font-mono'],
                ['Billing Period', $invoice->billing_period_start->format('M Y'), ''],
                ['Employees', $invoice->employee_count, ''],
            ] as [$label, $value, $cls])
            <div class="flex justify-between py-1 border-b border-gray-50">
                <span class="text-gray-400">{{ $label }}</span>
                <span class="text-gray-800 {{ $cls }}">{{ $value }}</span>
            </div>
            @endforeach
            <div class="flex justify-between pt-1">
                <span class="font-semibold text-gray-700">Total</span>
                <span class="font-bold text-gray-900 text-base">{{ format_currency($invoice->total_usd, 2) }}</span>
            </div>
        </dl>
    </div>

    <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-brand-900 rounded-2xl p-5 text-white">
        <p class="text-xs text-white/50 uppercase tracking-wide mb-1">Transfer To</p>
        <p class="text-xs text-white/70 mb-0.5">{{ $bankDetails['bank'] }}</p>
        <p class="text-lg font-mono font-semibold tracking-widest">{{ $bankDetails['account'] }}</p>
        <p class="text-sm text-white/60">{{ $bankDetails['holder'] }}</p>
        <p class="text-xs text-amber-300 mt-3">Use <strong>{{ $invoice->invoice_number }}</strong> as your transfer reference</p>
    </div>

    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-5">Payment Details</h3>
        <form method="POST" action="{{ route('dashboard.billing.pay.store', $invoice) }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="label">{{ __('billing.transfer_reference') }} *</label>
                    <input type="text" name="transfer_reference" value="{{ old('transfer_reference', $invoice->invoice_number) }}" required
                        class="input" placeholder="{{ $invoice->invoice_number }}">
                    <p class="text-xs text-gray-400 mt-1">Use your invoice number as the reference so we can match your payment</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">{{ __('billing.transfer_date') }} *</label>
                        <input type="date" name="transfer_date" value="{{ old('transfer_date', now()->format('Y-m-d')) }}" required class="input">
                    </div>
                    <div>
                        <label class="label">{{ __('billing.bank_used') }}</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" class="input" placeholder="e.g. ABA, ACLEDA">
                    </div>
                </div>
                <div>
                    <label class="label">Receipt / Screenshot <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-brand-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG or PDF · Max 5 MB</p>
                </div>
            </div>
            <div class="flex gap-3 mt-8">
                <button type="submit" class="btn-primary">{{ __('billing.submit') }}</button>
                <a href="{{ route('dashboard.billing.index') }}" class="btn-secondary">{{ __('common.cancel') }}</a>
            </div>
        </form>
    </div>

</div>

</x-layouts.dashboard>
