<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Payment — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.dashboard') }}</a>
            <a href="{{ route('dashboard.billing.index') }}" class="text-sm font-medium text-teal-600">{{ __('nav.billing') }}</a>
            <a href="{{ route('dashboard.employees.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.employees') }}</a>
            <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.gyms') }}</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.logout') }}</button>
            </form>
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs {{ App::getLocale() === 'en' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">EN</button>
                </form>
                <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline border-l border-gray-200">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs font-khmer {{ App::getLocale() === 'km' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">ខ្មែរ</button>
                </form>
            </div>
            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                <form method="POST" action="{{ route('currency.switch', 'usd') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs {{ $currency === 'usd' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">$ USD</button>
                </form>
                <form method="POST" action="{{ route('currency.switch', 'khr') }}" class="inline border-l border-gray-200">
                    @csrf
                    <button type="submit" class="px-2.5 py-1 text-xs {{ $currency === 'khr' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">៛ KHR</button>
                </form>
            </div>
            <div class="w-8 h-8 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-semibold">
                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
            </div>
        </div>
    </div>
</nav>

<div class="max-w-3xl mx-auto px-6 py-8">

    <a href="{{ route('dashboard.billing.index') }}" class="text-sm text-teal-600 hover:text-teal-700 mb-6 inline-block">← Back to Billing</a>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Submit Payment</h1>
        <p class="text-sm text-gray-400 mt-1">Invoice {{ $invoice->invoice_number }}</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Invoice Summary --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <h3 class="font-semibold mb-4">Invoice Summary</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-400">Invoice Number</span>
                <span class="font-mono text-gray-800">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Billing Period</span>
                <span class="text-gray-800">{{ $invoice->billing_period_start->format('M Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-400">Employees</span>
                <span class="text-gray-800">{{ $invoice->employee_count }}</span>
            </div>
            <div class="flex justify-between border-t border-gray-100 pt-3">
                <span class="font-semibold text-gray-700">Total</span>
                <span class="font-bold text-gray-800">{{ format_currency($invoice->total_usd, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Bank Details --}}
    <div class="bg-gradient-to-br from-gray-900 to-teal-900 rounded-xl p-5 text-white mb-6">
        <p class="text-xs text-white/50 uppercase tracking-wide mb-1">Transfer To</p>
        <p class="text-xs text-white/70 mb-0.5">{{ $bankDetails['bank'] }}</p>
        <p class="text-lg font-mono font-semibold tracking-widest">{{ $bankDetails['account'] }}</p>
        <p class="text-sm text-white/60">{{ $bankDetails['holder'] }}</p>
        <p class="text-xs text-amber-300 mt-3">⚠️ Use <strong>{{ $invoice->invoice_number }}</strong> as your transfer reference</p>
    </div>

    {{-- Payment Form --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="font-semibold mb-5">Payment Details</h3>

        <form method="POST" action="{{ route('dashboard.billing.pay.store', $invoice) }}" enctype="multipart/form-data">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('billing.transfer_reference') }} *</label>
                    <input type="text" name="transfer_reference" value="{{ old('transfer_reference', $invoice->invoice_number) }}" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500"
                        placeholder="e.g. {{ $invoice->invoice_number }}">
                    <p class="text-xs text-gray-400 mt-1">Use your invoice number as the reference so we can match your payment</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('billing.transfer_date') }} *</label>
                        <input type="date" name="transfer_date" value="{{ old('transfer_date', now()->format('Y-m-d')) }}" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('billing.bank_used') }}</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500"
                            placeholder="e.g. ABA, ACLEDA">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Receipt / Screenshot <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf"
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG or PDF · Max 5 MB</p>
                </div>

            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit"
                    class="bg-teal-600 text-white px-6 py-2.5 rounded-lg hover:bg-teal-700 transition font-medium">
                    {{ __('billing.submit') }}
                </button>
                <a href="{{ route('dashboard.billing.index') }}"
                    class="border border-gray-200 px-6 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>

</div>

</body>
</html>
