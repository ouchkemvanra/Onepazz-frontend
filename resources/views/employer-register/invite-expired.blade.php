<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Expired — OnePazz</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center h-16">
        <a href="/" class="flex items-center">
            <x-logo variant="dark" size="32" />
        </a>
    </div>
</nav>

<div class="max-w-md mx-auto px-6 py-24 text-center">
    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-900 mb-3">Invitation Expired</h1>
    <p class="text-gray-500 mb-8">This invitation link is no longer valid. Invitations expire after 14 days for security. Please contact the OnePazz team to request a new invitation.</p>

    <div class="card p-5 mb-8 text-left space-y-3">
        <p class="text-sm font-semibold text-gray-700">To get a new invitation:</p>
        <ul class="text-sm text-gray-500 space-y-2 list-disc list-inside">
            <li>Email us at <a href="mailto:hello@onepazz.kh" class="text-brand-600 hover:text-brand-700">hello@onepazz.kh</a></li>
            <li>Or <a href="{{ route('employer-register.create') }}" class="text-brand-600 hover:text-brand-700">register directly</a> without an invitation</li>
        </ul>
    </div>

    <div class="flex gap-3 justify-center">
        <a href="{{ route('employer-register.create') }}" class="btn-primary">Register Without Invitation</a>
        <a href="{{ route('home') }}" class="btn-secondary">Back to Home</a>
    </div>
</div>
</body>
</html>
