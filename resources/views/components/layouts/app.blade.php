<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'OnePazz' }} — Cambodia's Corporate Wellness Platform</title>
    <meta name="description" content="{{ $description ?? 'Connect your team with Cambodia\'s best gyms and fitness studios.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="bg-gray-50 min-h-screen {{ App::getLocale() === 'km' ? 'font-khmer' : 'font-sans' }}">

    <x-nav />

    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif
    @if(session('error'))
        <x-alert type="error" :message="session('error')" />
    @endif

    {{ $slot }}

    <x-footer />

</body>
</html>
