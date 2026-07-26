<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" class="{{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center relative {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

<div class="absolute top-4 right-4">
    <div class="flex items-center bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
            @csrf
            <button type="submit" class="px-3 py-1.5 text-xs font-medium {{ App::getLocale() === 'en' ? 'bg-brand-500 text-white' : 'text-gray-500 hover:bg-gray-50' }}">EN</button>
        </form>
        <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline border-l border-gray-200">
            @csrf
            <button type="submit" class="px-3 py-1.5 text-xs font-khmer {{ App::getLocale() === 'km' ? 'bg-brand-500 text-white' : 'text-gray-500 hover:bg-gray-50' }}">ខ្មែរ</button>
        </form>
    </div>
</div>

<div class="w-full max-w-md px-6 py-10">

    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center justify-center">
            <x-logo variant="dark" size="36" />
        </a>
        <h1 class="text-2xl font-bold mt-4 text-gray-900">{{ __('auth.sign_up_heading') }}</h1>
        <p class="text-sm text-gray-400 mt-1">Join Cambodia's corporate wellness network</p>
    </div>

    <div class="card p-8">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-xl px-4 py-3 mb-5">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="label">{{ __('auth.full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required autofocus class="input">
            </div>

            <div class="mb-4">
                <label class="label">{{ __('auth.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="input">
            </div>

            <div class="mb-4">
                <label class="label">Password</label>
                <input type="password" name="password" required class="input">
            </div>

            <div class="mb-6">
                <label class="label">Confirm password</label>
                <input type="password" name="password_confirmation" required class="input">
            </div>

            <button type="submit" class="btn-primary w-full justify-center">{{ __('auth.sign_up') }}</button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-400 mt-6">
        {{ __('auth.have_account') }}
        <a href="{{ route('login') }}" class="text-brand-600 font-medium hover:text-brand-700">{{ __('auth.sign_in') }}</a>
    </p>

</div>

</body>
</html>
