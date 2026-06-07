<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — OnePazz</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            OnePazz
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('dashboard.index') }}" class="text-sm font-medium text-teal-600">{{ __('nav.dashboard') }}</a>
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
    <div class="flex items-start justify-between mb-8" x-data="{ month: '{{ now()->format('Y-m') }}' }">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                {{ now()->hour < 12 ? __('dashboard.greeting_morning') : (now()->hour < 17 ? __('dashboard.greeting_afternoon') : __('dashboard.greeting_evening')) }},
                {{ explode(' ', auth()->user()->full_name)[0] }} 👋
            </h1>
            <p class="text-sm text-gray-400 mt-1">
                {{ $employer->company_name }} —
                {{ $sub?->plan?->name ?? __('dashboard.no_plan') }} Plan ·
                {{ $activeEmployees }} {{ __('dashboard.active_employees') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <input type="month" x-model="month"
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-teal-500 bg-white">
            <a :href="`{{ url('/dashboard/reports/csv') }}?month=${month}`"
               class="text-sm border border-gray-200 bg-white px-4 py-2 rounded-lg hover:bg-gray-50 transition">
                📥 {{ __('dashboard.export_csv') }}
            </a>
            <a :href="`{{ url('/dashboard/reports/pdf') }}?month=${month}`"
               class="text-sm bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                📄 {{ __('dashboard.export_pdf') }}
            </a>
        </div>
    </div>

    {{-- Onboarding Banner (shown until first employee is added) --}}
    @if($activeEmployees === 0)
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-6 mb-6" x-data="{ open: true }" x-show="open">
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="font-semibold text-teal-800 text-lg">Welcome to OnePazz! Let's get you set up.</p>
                <p class="text-sm text-teal-600 mt-0.5">Complete these steps to activate your corporate wellness programme.</p>
            </div>
            <button @click="open = false" class="text-teal-400 hover:text-teal-600 ml-4 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            {{-- Step 1: Account Active --}}
            <div class="bg-white rounded-lg p-4 border border-teal-100 flex items-start gap-3">
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
                    {{ $employer->status === 'active' ? 'bg-teal-600' : 'bg-gray-200' }}">
                    @if($employer->status === 'active')
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <span class="text-xs text-gray-500 font-bold">1</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Registration approved</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if($employer->status === 'active') Done! Your account is active.
                        @else Pending admin review (within 24h).
                        @endif
                    </p>
                </div>
            </div>
            {{-- Step 2: Add Employees --}}
            <div class="bg-white rounded-lg p-4 border border-teal-100 flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center shrink-0 mt-0.5">
                    <span class="text-xs text-gray-500 font-bold">2</span>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Add your first employee</p>
                    <p class="text-xs text-gray-400 mt-0.5">Invite staff members to start using OnePazz.</p>
                    @if($employer->status === 'active')
                    <a href="{{ route('dashboard.employees.create') }}" class="inline-block mt-2 text-xs bg-teal-600 text-white px-3 py-1 rounded-lg hover:bg-teal-700">Add Employees →</a>
                    @endif
                </div>
            </div>
            {{-- Step 3: Pay Invoice --}}
            <div class="bg-white rounded-lg p-4 border border-teal-100 flex items-start gap-3">
                @php $pendingInvoice = $employer->invoices()->where('status','unpaid')->latest()->first(); @endphp
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
                    {{ !$pendingInvoice ? 'bg-teal-600' : 'bg-gray-200' }}">
                    @if(!$pendingInvoice)
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    @else
                    <span class="text-xs text-gray-500 font-bold">3</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-800">Pay your first invoice</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        @if($pendingInvoice) Invoice {{ $pendingInvoice->invoice_number }} is awaiting payment.
                        @else No outstanding invoices.
                        @endif
                    </p>
                    @if($pendingInvoice)
                    <a href="{{ route('dashboard.billing.pay', $pendingInvoice) }}" class="inline-block mt-2 text-xs bg-orange-500 text-white px-3 py-1 rounded-lg hover:bg-orange-600">Pay Now →</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- KPI Metrics --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('dashboard.active_employees') }}</p>
            <p class="text-3xl font-bold text-teal-600">{{ $activeEmployees }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('dashboard.on_current_plan') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('dashboard.checkins_month') }} ({{ now()->format('M') }})</p>
            <p class="text-3xl font-bold text-gray-800">{{ $checkinsThisMonth }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('dashboard.this_month') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('dashboard.monthly_cost') }}</p>
            <p class="text-3xl font-bold text-gray-800">{{ format_currency($monthlyCostUsd) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $activeEmployees }} × {{ format_currency($sub?->plan?->price_usd ?? 0) }}/mo</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">{{ __('dashboard.utilisation') }}</p>
            <p class="text-3xl font-bold text-orange-500">{{ $utilisationRate }}%</p>
            <p class="text-xs text-gray-400 mt-1">{{ __('dashboard.employees_active') }}</p>
        </div>
    </div>

    {{-- Charts row --}}
    <div class="grid grid-cols-3 gap-5 mb-6">

        {{-- Weekly bar chart --}}
        <div class="col-span-2 bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-5">
                <h3 class="font-semibold">{{ __('dashboard.weekly_checkins') }}</h3>
                <span class="text-xs text-gray-400">{{ __('dashboard.last_7_days') }}</span>
            </div>
            @php $maxCheckins = $weeklyCheckins->max('count') ?: 1; @endphp
            <div class="flex items-end gap-2 h-32">
                @foreach($weeklyCheckins as $day)
                <div class="flex flex-col items-center flex-1 h-full justify-end gap-1">
                    <span class="text-xs text-gray-400">{{ $day['count'] }}</span>
                    <div class="w-full rounded-t transition-all
                        {{ $day['today'] ? 'bg-teal-500' : 'bg-teal-100 border border-teal-200' }}"
                        style="height: {{ round($day['count'] / $maxCheckins * 100) }}%">
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap">{{ $day['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Top gyms --}}
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-semibold mb-4">{{ __('dashboard.top_gyms') }}</h3>
            @forelse($topGyms as $g)
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-600 truncate">{{ $g->gym?->name }}</span>
                    <span class="text-gray-400 ml-2 flex-shrink-0">{{ $g->visits }}</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-teal-500 rounded-full"
                        style="width: {{ round($g->visits / $topGyms->first()->visits * 100) }}%">
                    </div>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-400">{{ __('dashboard.no_visits') }}</p>
            @endforelse
        </div>
    </div>

    {{-- Employee table --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-semibold">{{ __('dashboard.recent_activity') }}</h3>
            <a href="{{ route('dashboard.employees.create') }}" class="text-sm bg-teal-50 text-teal-600 border border-teal-200 px-3 py-1.5 rounded-lg hover:bg-teal-100 transition">
                {{ __('dashboard.invite_employee') }}
            </a>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">{{ __('employee.name') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">{{ __('employee.department') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">{{ __('employee.card_no') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">{{ __('employee.visits') }} ({{ now()->format('M') }})</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide pb-3">{{ __('employee.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-teal-500 text-white text-xs flex items-center justify-center font-semibold flex-shrink-0">
                                {{ strtoupper(substr($employee->user->full_name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $employee->user->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $employee->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 text-gray-500">{{ $employee->department ?? '—' }}</td>
                    <td class="py-3 font-mono text-xs text-gray-400">{{ $employee->membership_card_no }}</td>
                    <td class="py-3 font-semibold text-gray-800">{{ $employee->checkins_count }}</td>
                    <td class="py-3">
                        @if($employee->status === 'active')
                            <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-medium px-2 py-0.5 rounded-full">{{ __('employee.active') }}</span>
                        @elseif($employee->status === 'suspended')
                            <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-medium px-2 py-0.5 rounded-full">{{ __('employee.suspended') }}</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs font-medium px-2 py-0.5 rounded-full">{{ __('employee.inactive') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-gray-400 text-sm">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

</body>
</html>