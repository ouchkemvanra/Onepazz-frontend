<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Received — KhmerFit</title>
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

<div class="max-w-2xl mx-auto px-6 py-16 text-center">

    {{-- Success Icon --}}
    <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-2">Registration Received!</h1>
    <p class="text-gray-500 mb-10">Thank you for registering with KhmerFit. Our team will review your application within 24 hours and activate your account once verified.</p>

    {{-- Reference Code --}}
    @if($refCode)
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 mb-6 text-left">
        <p class="text-xs font-semibold text-teal-600 uppercase tracking-wide mb-1">Your Reference Code</p>
        <p class="text-2xl font-bold font-mono text-teal-700">{{ $refCode }}</p>
        <p class="text-xs text-teal-600 mt-1">Quote this in all communications with our team.</p>
    </div>
    @endif

    {{-- Bank Transfer Instructions --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 text-left">
        <h2 class="font-semibold text-gray-800 mb-4">Bank Transfer Instructions</h2>
        <p class="text-sm text-gray-500 mb-4">To complete your registration, please transfer the invoice amount to our bank account below. Use your reference code as the transfer description.</p>

        <div class="bg-gray-50 rounded-lg p-4 space-y-3 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Bank</span>
                <span class="font-medium text-gray-800">{{ $bankDetails['bank'] }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Account Number</span>
                <span class="font-mono font-medium text-gray-800">{{ $bankDetails['account'] }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Account Name</span>
                <span class="font-medium text-gray-800">{{ $bankDetails['holder'] }}</span>
            </div>
            @if($refCode)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Transfer Description</span>
                <span class="font-mono font-medium text-teal-700">{{ $refCode }}</span>
            </div>
            @endif
        </div>

        @if($totalUsd || $totalKhr)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-xs text-gray-400 mb-2 uppercase tracking-wide font-semibold">Invoice Amount</p>
            <div class="flex items-baseline gap-3">
                @if($totalUsd)
                <span class="text-2xl font-bold text-gray-800 font-mono">${{ number_format($totalUsd, 2) }}</span>
                @endif
                @if($totalKhr)
                <span class="text-sm text-gray-400 font-mono">≈ ៛{{ number_format($totalKhr) }}</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Next Steps --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8 text-left">
        <h2 class="font-semibold text-gray-800 mb-4">What Happens Next</h2>
        <div class="space-y-4">
            @foreach([
                ['1', 'Check your email', 'A confirmation email has been sent with these bank details and your reference code.'],
                ['2', 'Make the transfer', 'Transfer the invoice amount using your reference code as the description.'],
                ['3', 'Admin review (24h)', 'Our team will verify your payment and activate your account within one business day.'],
                ['4', 'Get started', 'You\'ll receive login credentials and can begin adding employees immediately.'],
            ] as [$n, $title, $desc])
            <div class="flex gap-4">
                <div class="w-7 h-7 rounded-full bg-teal-100 text-teal-700 text-xs font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $n }}</div>
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-teal-600 hover:text-teal-700 text-sm font-medium">
        ← Back to KhmerFit
    </a>
</div>
</body>
</html>
