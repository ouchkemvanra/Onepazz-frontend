<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invite Employee — OnePazz</title>
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

<div class="max-w-3xl mx-auto px-6 py-8">

    {{-- Back Link --}}
    <a href="{{ route('dashboard.employees.index') }}" class="text-sm text-teal-600 hover:text-teal-700 mb-6 inline-block">← Back to Employees</a>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('employee.invite_heading') }}</h1>
        <p class="text-sm text-gray-400 mt-1">Add a new employee to {{ $employer->company_name }}</p>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <form method="POST" action="{{ route('dashboard.employees.store') }}">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('employee.full_name') }} *</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-teal-500" placeholder="John Doe">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('employee.email') }} *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-teal-500" placeholder="john@example.com">
                    <p class="text-xs text-gray-400 mt-1">The employee will receive login credentials at this email address</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('employee.department') }}</label>
                        <input type="text" name="department" value="{{ old('department') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-teal-500" placeholder="e.g. Marketing">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('employee.job_title') }}</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-teal-500" placeholder="e.g. Manager">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee Code</label>
                    <input type="text" name="employee_code" value="{{ old('employee_code') }}" class="w-full border border-gray-200 rounded-lg px-4 py-2 focus:outline-none focus:border-teal-500" placeholder="Optional internal employee ID">
                </div>

            </div>

            <div class="flex gap-3 mt-8">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition">
                    {{ __('employee.send_invite') }}
                </button>
                <a href="{{ route('dashboard.employees.index') }}" class="border border-gray-200 px-6 py-2 rounded-lg hover:bg-gray-50 transition">
                    {{ __('common.cancel') }}
                </a>
            </div>
        </form>
    </div>

    {{-- Info Box --}}
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-700">
            <strong>Note:</strong> A membership card number will be automatically generated for this employee. They will be assigned to your company's active subscription.
        </p>
    </div>

</div>

</body>
</html>
