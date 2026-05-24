<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing — KhmerFit</title>
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

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('billing.billing') }}</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $employer->company_name }} · View invoices and submit bank transfer payments</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center">
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    {{-- Bank Transfer Details --}}
    <div class="bg-gradient-to-br from-gray-900 to-teal-900 rounded-xl p-6 text-white mb-8 max-w-lg">
        <p class="text-xs text-white/50 uppercase tracking-wide mb-1">{{ $bankDetails['bank'] }} · Corporate Account</p>
        <p class="text-xl font-mono font-semibold tracking-widest mb-1">{{ $bankDetails['account'] }}</p>
        <p class="text-sm text-white/70 mb-4">{{ $bankDetails['holder'] }}</p>
        <div class="bg-white/10 rounded-lg p-3 text-xs text-white/60 leading-relaxed">
            ⚠️ Use your invoice number as the transfer reference.<br>
            Confirmation is processed within 1–2 business days.<br>
            Contact billing@khmerfit.com.kh for queries.
        </div>
    </div>

    {{-- Invoice List --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="font-semibold">Invoice History</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('billing.invoice_no') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('billing.period') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('employee.employees') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ $currency === 'khr' ? __('billing.amount_khr') : __('billing.amount_usd') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('billing.status') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('billing.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $invoice->invoice_number }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $invoice->billing_period_start->format('M Y') }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $invoice->employee_count }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ format_currency($invoice->total_usd, 2) }}</td>
                    <td class="px-6 py-4">
                        @if($invoice->status === 'paid')
                            <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-medium px-2 py-1 rounded-full">{{ __('billing.confirmed') }}</span>
                        @elseif($invoice->status === 'pending_verification')
                            <span class="bg-amber-50 text-amber-700 border border-amber-200 text-xs font-medium px-2 py-1 rounded-full">{{ __('billing.pending_review') }}</span>
                        @elseif($invoice->status === 'overdue')
                            <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-medium px-2 py-1 rounded-full">{{ __('billing.overdue') }}</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 border border-gray-200 text-xs font-medium px-2 py-1 rounded-full">{{ __('billing.unpaid') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if(in_array($invoice->status, ['unpaid', 'overdue']))
                            <a href="{{ route('dashboard.billing.pay', $invoice) }}"
                               class="bg-teal-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg hover:bg-teal-700 transition">
                                {{ __('billing.submit_payment') }}
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center text-gray-400 text-sm">
                        {{ __('billing.no_invoices') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>

</div>

</body>
</html>
