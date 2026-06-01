<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Expired — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit
        </a>
    </div>
</nav>

<div class="max-w-md mx-auto px-6 py-24 text-center">
    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-800 mb-3">Invitation Expired</h1>
    <p class="text-gray-500 mb-8">This invitation link is no longer valid. Invitations expire after 14 days for security. Please contact the KhmerFit team to request a new invitation.</p>

    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-8 text-left space-y-3">
        <p class="text-sm font-medium text-gray-700">To get a new invitation:</p>
        <ul class="text-sm text-gray-500 space-y-2 list-disc list-inside">
            <li>Email us at <a href="mailto:hello@khmerfit.kh" class="text-teal-600 hover:underline">hello@khmerfit.kh</a></li>
            <li>Or <a href="{{ route('employer-register.create') }}" class="text-teal-600 hover:underline">register directly</a> without an invitation</li>
        </ul>
    </div>

    <div class="flex gap-3 justify-center">
        <a href="{{ route('employer-register.create') }}" class="bg-teal-600 text-white px-6 py-2.5 rounded-lg hover:bg-teal-700 text-sm font-medium">Register Without Invitation</a>
        <a href="{{ route('home') }}" class="border border-gray-200 px-6 py-2.5 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Back to Home</a>
    </div>
</div>
</body>
</html>
