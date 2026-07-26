<x-layouts.dashboard title="{{ __('nav.dashboard') }}">

{{-- Greeting + actions --}}
<div class="flex items-start justify-between mb-8" x-data="{ month: '{{ now()->format('Y-m') }}' }">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            {{ now()->hour < 12 ? __('dashboard.greeting_morning') : (now()->hour < 17 ? __('dashboard.greeting_afternoon') : __('dashboard.greeting_evening')) }},
            {{ explode(' ', auth()->user()->full_name)[0] }} 👋
        </h1>
        <p class="text-sm text-gray-400 mt-1">
            {{ $employer->company_name }} ·
            {{ $sub?->plan?->name ?? __('dashboard.no_plan') }} Plan ·
            {{ $activeEmployees }} {{ __('dashboard.active_employees') }}
        </p>
    </div>
    <div class="flex items-center gap-2">
        <input type="month" x-model="month"
            class="input text-sm py-2">
        <a :href="`{{ url('/dashboard/reports/csv') }}?month=${month}`" class="btn-secondary text-sm py-2">
            📥 CSV
        </a>
        <a :href="`{{ url('/dashboard/reports/pdf') }}?month=${month}`" class="btn-primary text-sm py-2">
            📄 PDF
        </a>
    </div>
</div>

{{-- Onboarding banner --}}
@if($activeEmployees === 0)
<div class="bg-gradient-to-r from-brand-50 to-warm-50 border border-brand-200 rounded-2xl p-6 mb-8"
     x-data="{ open: true }" x-show="open">
    <div class="flex items-start justify-between mb-5">
        <div>
            <p class="font-bold text-brand-900 text-lg">Welcome to OnePazz! Let's get you set up 🎉</p>
            <p class="text-sm text-brand-600 mt-0.5">Complete these steps to activate your corporate wellness programme.</p>
        </div>
        <button @click="open = false" class="text-brand-300 hover:text-brand-500 ml-4 shrink-0 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        {{-- Step 1 --}}
        <div class="bg-white rounded-xl p-4 border border-brand-100 flex items-start gap-3 shadow-sm">
            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ $employer->status === 'active' ? 'bg-brand-500' : 'bg-gray-200' }}">
                @if($employer->status === 'active')
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                @else
                <span class="text-xs text-gray-500 font-bold">1</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Registration approved</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if($employer->status === 'active') ✓ Your account is active.
                    @else Pending admin review (within 24h).
                    @endif
                </p>
            </div>
        </div>
        {{-- Step 2 --}}
        <div class="bg-white rounded-xl p-4 border border-brand-100 flex items-start gap-3 shadow-sm">
            <div class="w-7 h-7 rounded-full bg-gray-200 flex items-center justify-center shrink-0 mt-0.5">
                <span class="text-xs text-gray-500 font-bold">2</span>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Add your first employee</p>
                <p class="text-xs text-gray-400 mt-0.5">Invite staff to start using OnePazz.</p>
                @if($employer->status === 'active')
                <a href="{{ route('dashboard.employees.create') }}" class="btn-primary text-xs px-3 py-1.5 mt-2 inline-flex">
                    Add Employees →
                </a>
                @endif
            </div>
        </div>
        {{-- Step 3 --}}
        <div class="bg-white rounded-xl p-4 border border-brand-100 flex items-start gap-3 shadow-sm">
            @php $pendingInvoice = $employer->invoices()->where('status','unpaid')->latest()->first(); @endphp
            <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5 {{ !$pendingInvoice ? 'bg-brand-500' : 'bg-gray-200' }}">
                @if(!$pendingInvoice)
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                @else
                <span class="text-xs text-gray-500 font-bold">3</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Pay your first invoice</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    @if($pendingInvoice) {{ $pendingInvoice->invoice_number }} awaiting payment.
                    @else No outstanding invoices.
                    @endif
                </p>
                @if($pendingInvoice)
                <a href="{{ route('dashboard.billing.pay', $pendingInvoice) }}" class="btn-primary text-xs px-3 py-1.5 mt-2 inline-flex bg-warm-500 hover:bg-warm-600 border-warm-500">
                    Pay Now →
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

{{-- KPI cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('dashboard.active_employees') }}</p>
        <p class="text-3xl font-bold text-brand-600">{{ $activeEmployees }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ __('dashboard.on_current_plan') }}</p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('dashboard.checkins_month') }}</p>
        <p class="text-3xl font-bold text-gray-800">{{ $checkinsThisMonth }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ now()->format('F') }}</p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('dashboard.monthly_cost') }}</p>
        <p class="text-3xl font-bold text-gray-800">{{ format_currency($monthlyCostUsd) }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $activeEmployees }} × {{ format_currency($sub?->plan?->price_usd ?? 0) }}/mo</p>
    </div>
    <div class="card p-5">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('dashboard.utilisation') }}</p>
        <p class="text-3xl font-bold text-warm-500">{{ $utilisationRate }}%</p>
        <p class="text-xs text-gray-400 mt-1">{{ __('dashboard.employees_active') }}</p>
    </div>
</div>

{{-- Charts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    {{-- Weekly bar chart --}}
    <div class="lg:col-span-2 card p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-gray-900">{{ __('dashboard.weekly_checkins') }}</h3>
            <span class="text-xs text-gray-400">{{ __('dashboard.last_7_days') }}</span>
        </div>
        @php $maxCheckins = $weeklyCheckins->max('count') ?: 1; @endphp
        <div class="flex items-end gap-2 h-32">
            @foreach($weeklyCheckins as $day)
            <div class="flex flex-col items-center flex-1 h-full justify-end gap-1.5">
                <span class="text-xs text-gray-400 font-medium">{{ $day['count'] ?: '' }}</span>
                <div class="w-full rounded-t-lg transition-all {{ $day['today'] ? 'bg-brand-500' : 'bg-brand-100' }}"
                    style="height: {{ max(4, round($day['count'] / $maxCheckins * 100)) }}%">
                </div>
                <span class="text-xs text-gray-400">{{ $day['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Top gyms --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-900 mb-5">{{ __('dashboard.top_gyms') }}</h3>
        @forelse($topGyms as $g)
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-1.5">
                <span class="text-gray-700 font-medium truncate">{{ $g->gym?->name }}</span>
                <span class="text-gray-400 ml-2 shrink-0 text-xs font-semibold">{{ $g->visits }}</span>
            </div>
            <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-brand-400 rounded-full transition-all"
                    style="width: {{ round($g->visits / max($topGyms->first()->visits, 1) * 100) }}%">
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-8">
            <div class="text-3xl mb-2">🏋️</div>
            <p class="text-sm text-gray-400">{{ __('dashboard.no_visits') }}</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Employee table --}}
<div class="card">
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
        <h3 class="font-bold text-gray-900">{{ __('dashboard.recent_activity') }}</h3>
        <a href="{{ route('dashboard.employees.create') }}" class="btn-primary text-sm py-1.5">
            + {{ __('dashboard.invite_employee') }}
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="table-head">{{ __('employee.name') }}</th>
                    <th class="table-head">{{ __('employee.department') }}</th>
                    <th class="table-head">{{ __('employee.card_no') }}</th>
                    <th class="table-head">{{ __('employee.visits') }} ({{ now()->format('M') }})</th>
                    <th class="table-head">{{ __('employee.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr class="table-row">
                    <td class="table-cell">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-brand-500 text-white text-xs flex items-center justify-center font-bold shrink-0">
                                {{ strtoupper(substr($employee->user->full_name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-800 text-sm">{{ $employee->user->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $employee->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="table-cell text-gray-500 text-sm">{{ $employee->department ?? '—' }}</td>
                    <td class="table-cell font-mono text-xs text-gray-400">{{ $employee->membership_card_no }}</td>
                    <td class="table-cell font-bold text-gray-800">{{ $employee->checkins_count }}</td>
                    <td class="table-cell">
                        @if($employee->status === 'active')
                            <span class="badge-green">{{ __('employee.active') }}</span>
                        @elseif($employee->status === 'suspended')
                            <span class="badge-red">{{ __('employee.suspended') }}</span>
                        @else
                            <span class="badge-gray">{{ __('employee.inactive') }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <div class="text-4xl mb-3">👥</div>
                        <p class="text-gray-500 font-medium mb-1">No employees yet</p>
                        <p class="text-gray-400 text-sm mb-4">Add your first employee to get started.</p>
                        <a href="{{ route('dashboard.employees.create') }}" class="btn-primary text-sm">Add Employees</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-layouts.dashboard>
