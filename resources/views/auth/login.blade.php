<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6 {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

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

<div class="w-full max-w-sm">

    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('auth.sign_in_heading') }}</h1>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded p-3 mb-4">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email') }}</label>
                <input type="email" name="email" required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.password') }}</label>
                <input type="password" name="password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>

            <button type="submit"
                class="w-full bg-teal-600 text-white font-semibold py-2 rounded text-sm">
                {{ __('auth.sign_in') }}
            </button>
        </form>
    </div>

    <div class="mt-4 bg-white rounded-xl border border-gray-200 p-4">
        <p class="text-xs text-gray-400 mb-2">Quick login:</p>
        <button onclick="fillLogin('admin@khmerfit.com.kh','adminpassword!')"
            class="block w-full text-left text-xs text-gray-600 hover:bg-gray-50 px-2 py-1.5 rounded">
            Platform Admin — admin@khmerfit.com.kh
        </button>
        <button onclick="fillLogin('sokha@smartretail.com.kh','password123')"
            class="block w-full text-left text-xs text-gray-600 hover:bg-gray-50 px-2 py-1.5 rounded">
            Employer Admin — sokha@smartretail.com.kh
        </button>
        <button onclick="fillLogin('dara@smartretail.com.kh','password123')"
            class="block w-full text-left text-xs text-gray-600 hover:bg-gray-50 px-2 py-1.5 rounded">
            Member — dara@smartretail.com.kh
        </button>
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