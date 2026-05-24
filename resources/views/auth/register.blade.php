<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center relative {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

<div class="absolute top-4 right-4 flex items-center border border-gray-200 rounded-lg overflow-hidden">
    <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
        @csrf
        <button type="submit" class="px-2.5 py-1 text-xs {{ App::getLocale() === 'en' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">EN</button>
    </form>
    <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline border-l border-gray-200">
        @csrf
        <button type="submit" class="px-2.5 py-1 text-xs font-khmer {{ App::getLocale() === 'km' ? 'bg-teal-600 text-white' : 'text-gray-500 hover:bg-gray-50' }}">ខ្មែរ</button>
    </form>
</div>

<div class="w-full max-w-md px-6 py-10">

    <div class="text-center mb-8">
        <a href="/" class="inline-flex items-center gap-2 text-teal-600 font-bold text-xl">
            <div class="w-9 h-9 bg-teal-600 rounded-xl flex items-center justify-center text-white">🏃</div>
            KhmerFit
        </a>
        <h1 class="text-2xl font-bold mt-4 text-gray-800">{{ __('auth.sign_up_heading') }}</h1>
        <p class="text-sm text-gray-400 mt-1">Join Cambodia's corporate wellness network</p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-5">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.full_name') }}</label>
                <input type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ __('auth.email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-teal-500">
            </div>

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-700 text-white font-semibold py-2.5 rounded-lg text-sm">
                {{ __('auth.sign_up') }}
            </button>
        </form>
    </div>

    <p class="text-center text-sm text-gray-400 mt-6">
        {{ __('auth.have_account') }}
        <a href="{{ route('login') }}" class="text-teal-600 font-medium hover:underline">{{ __('auth.sign_in') }}</a>
    </p>

</div>

</body>
</html>