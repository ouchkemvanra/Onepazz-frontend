<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.sign_in') }} — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

<div class="min-h-screen flex">

    {{-- Left brand panel (desktop only) --}}
    <div class="hidden lg:flex flex-col justify-between w-1/2 bg-gradient-to-br from-brand-600 via-brand-500 to-warm-500 p-12 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -top-20 -right-20 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 -left-10 w-64 h-64 bg-warm-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="relative">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <x-logo variant="white" size="36" />
                <span class="font-bold text-white text-xl">OnePazz</span>
            </a>
        </div>

        <div class="relative">
            <h2 class="text-3xl font-bold text-white leading-snug mb-4">
                Cambodia's Corporate<br>Wellness Platform
            </h2>
            <p class="text-white/70 text-base leading-relaxed mb-8">
                Connect your team with 120+ gyms and fitness studios across the Kingdom.
            </p>
            <div class="grid grid-cols-2 gap-4">
                @foreach([['120+', 'Partner Gyms'], ['5,200+', 'Active Members'], ['48+', 'Companies', ], ['7', 'Provinces']] as [$n, $l])
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/15">
                    <div class="text-2xl font-bold text-white">{{ $n }}</div>
                    <div class="text-white/60 text-xs mt-0.5">{{ $l }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <p class="relative text-white/40 text-xs">© {{ date('Y') }} OnePazz Co., Ltd · Phnom Penh, Cambodia</p>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex flex-col">

        {{-- Mobile logo --}}
        <div class="lg:hidden flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <x-logo variant="dark" size="28" />
            </a>
        </div>

        <div class="flex-1 flex items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-sm">

                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('auth.sign_in_heading') }}</h1>
                    <p class="text-gray-500 text-sm mt-1">Welcome back — sign in to your account</p>
                </div>

                {{-- Error --}}
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-2xl p-4 mb-6 flex items-start gap-2">
                    <span class="shrink-0 font-bold">✕</span>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="label">{{ __('auth.email') }}</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input" placeholder="you@company.com">
                    </div>

                    <div>
                        <label class="label">{{ __('auth.password') }}</label>
                        <input type="password" name="password" required
                            class="input" placeholder="••••••••">
                    </div>

                    <button type="submit" class="btn-primary w-full justify-center py-3 mt-2">
                        {{ __('auth.sign_in') }}
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-6">
                    Don't have an account?
                    <a href="{{ route('employer-register.create') }}" class="text-brand-600 font-semibold hover:text-brand-700">Register your company</a>
                </p>

                {{-- Language toggle --}}
                <div class="flex justify-center mt-8">
                    <div class="flex items-center bg-gray-100 rounded-xl p-0.5">
                        <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ App::getLocale() === 'en' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">EN</button>
                        </form>
                        <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium font-khmer rounded-lg transition-all {{ App::getLocale() === 'km' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">ខ្មែរ</button>
                        </form>
                    </div>
                </div>

                @if(config('app.env') === 'local')
                <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4">
                    <p class="text-xs font-semibold text-amber-700 mb-2">Quick login (dev only)</p>
                    @foreach([
                        ['admin@onepazz.com.kh', 'adminpassword!', 'Platform Admin'],
                        ['sokha@smartretail.com.kh', 'password123', 'Employer Admin'],
                        ['dara@smartretail.com.kh', 'password123', 'Member'],
                        ['gymadmin@fitrepublic.com.kh', 'password123', 'Gym Admin'],
                        ['cashier@fitrepublic.com.kh', 'password123', 'Cashier'],
                        ['manager@fitrepublic.com.kh', 'password123', 'Manager'],
                    ] as [$email, $pass, $label])
                    <button onclick="fillLogin('{{ $email }}','{{ $pass }}')"
                        class="block w-full text-left text-xs text-amber-800 hover:bg-amber-100 px-2 py-1.5 rounded-lg transition-colors">
                        <span class="font-medium">{{ $label }}</span> — {{ $email }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(email, password) {
    document.querySelector('input[name=email]').value = email;
    document.querySelector('input[name=password]').value = password;
}
</script>

</body>
</html>
