<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management — OnePazz</title>
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
            <a href="{{ route('dashboard.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.dashboard') }}</a>
            <a href="{{ route('gyms.index') }}" class="text-sm text-gray-500 hover:text-gray-800">{{ __('nav.gyms') }}</a>
            <a href="{{ route('dashboard.employees.index') }}" class="text-sm font-medium text-teal-600">{{ __('nav.employees') }}</a>
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
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ __('employee.employees') }}</h1>
            <p class="text-sm text-gray-400 mt-1">{{ $employer->company_name }} · {{ $employees->total() }} employees</p>
        </div>
        <a href="{{ route('dashboard.employees.create') }}" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
            {{ __('employee.invite_new') }}
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    {{-- Employees Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('employee.name') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('employee.department') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('employee.card_no') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('dashboard.checkins_month') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('employee.status') }}</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">{{ __('billing.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-teal-500 text-white text-xs flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($employee->user->full_name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-800">{{ $employee->user->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $employee->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $employee->department ?? '—' }}</td>
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $employee->membership_card_no }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $employee->checkins_count }}</td>
                    <td class="px-6 py-4">
                        @if($employee->status === 'active')
                            <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-medium px-2 py-1 rounded-full">{{ __('employee.active') }}</span>
                        @elseif($employee->status === 'suspended')
                            <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-medium px-2 py-1 rounded-full">{{ __('employee.suspended') }}</span>
                        @else
                            <span class="bg-gray-100 text-gray-500 text-xs font-medium px-2 py-1 rounded-full">{{ ucfirst($employee->status) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($employee->status === 'active')
                            <form method="POST" action="{{ route('dashboard.employees.suspend', $employee) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('Suspend this employee?')" class="text-red-600 hover:text-red-700 font-medium">{{ __('employee.suspend') }}</button>
                            </form>
                        @elseif($employee->status === 'suspended')
                            <form method="POST" action="{{ route('dashboard.employees.restore', $employee) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-700 font-medium">{{ __('employee.restore') }}</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-12 text-center text-gray-400 text-sm">No employees found. <a href="{{ route('dashboard.employees.create') }}" class="text-teal-600 hover:underline">Invite your first employee</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($employees->hasPages())
    <div class="mt-6">
        {{ $employees->links() }}
    </div>
    @endif

</div>

</body>
</html>
