<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Check-in — {{ $gym->name }} — KhmerFit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-2xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">QR Check-in Code</h1>
        <p class="text-sm text-gray-400 mt-1">Post this at your entrance for member self check-in</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">{{ session('success') }}</div>
    @endif

    {{-- QR Code Display --}}
    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center mb-6">
        <div class="inline-block p-4 bg-white rounded-xl shadow-sm border border-gray-100 mb-4">
            {!! $qrSvg !!}
        </div>
        <p class="text-xs font-mono text-gray-400 mt-2">{{ $gym->qr_code }}</p>
        <p class="text-sm text-gray-500 mt-3">{{ $gym->name }}</p>
    </div>

    {{-- Instructions --}}
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-5 mb-6">
        <h3 class="font-semibold text-teal-800 mb-2">Print Instructions</h3>
        <p class="text-sm text-teal-700">Post this QR code at your entrance. Members scan it with the KhmerFit app to check in automatically. Members must be within <strong>{{ $gym->checkin_radius_meters ?? 50 }}m</strong> of your gym to check in.</p>
    </div>

    {{-- Actions --}}
    <div class="flex gap-3 flex-wrap">
        <a href="{{ route('gym-portal.qr-code') }}?download=1"
           class="bg-teal-600 text-white px-5 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-medium">
            Download PNG
        </a>
        <button onclick="window.print()" class="border border-gray-200 px-5 py-2 rounded-lg hover:bg-gray-50 transition text-sm text-gray-600">
            Print
        </button>
        <form method="POST" action="{{ route('gym-portal.qr-code.regenerate') }}" onsubmit="return confirm('Regenerate QR code? The current QR will stop working immediately.')">
            @csrf
            <button type="submit" class="border border-red-200 text-red-600 px-5 py-2 rounded-lg hover:bg-red-50 transition text-sm">
                Regenerate QR Code
            </button>
        </form>
    </div>

    <p class="text-xs text-gray-400 mt-4">Current check-in radius: <strong>{{ $gym->checkin_radius_meters ?? 50 }}m</strong>. Adjust in your gym settings under Admin → Gyms.</p>
</div>
</body>
</html>
