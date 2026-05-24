<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Applications — Admin — KhmerFit</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@300;400;500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit Admin
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-800">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-gray-500 hover:text-gray-800">Payments</a>
            <a href="{{ route('admin.gym-applications.index') }}" class="text-sm font-medium text-teal-600">Gym Applications</a>
            <a href="{{ route('admin.settings') }}" class="text-sm text-gray-500 hover:text-gray-800">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800">Logout</button>
            </form>
            <div class="w-8 h-8 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-semibold">
                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
            </div>
        </div>
    </div>
</nav>

<div class="max-w-7xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Gym Applications</h1>
        <p class="text-sm text-gray-400 mt-1">Review and approve gym partner applications</p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    {{-- Applications Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="border-b border-gray-200">
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">ID</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Studio Name</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Contact</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Location</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Applied</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Status</th>
                    <th class="text-left text-xs font-semibold text-gray-400 uppercase tracking-wide px-6 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-gray-400">#{{ $app->id }}</td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-800">{{ $app->studio_name }}</div>
                        @if($app->studio_name_kh)
                        <div class="text-xs text-gray-400">{{ $app->studio_name_kh }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-gray-800">{{ $app->contact_name }}</div>
                        <div class="text-xs text-gray-400">{{ $app->contact_email }}</div>
                        <div class="text-xs text-gray-400">{{ $app->contact_phone }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $app->district }}, {{ $app->city }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $app->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4">
                        @if($app->status === 'pending')
                            <span class="bg-orange-50 text-orange-600 border border-orange-200 text-xs font-medium px-2 py-1 rounded-full">Pending</span>
                        @elseif($app->status === 'approved')
                            <span class="bg-green-50 text-green-700 border border-green-200 text-xs font-medium px-2 py-1 rounded-full">Approved</span>
                        @elseif($app->status === 'rejected')
                            <span class="bg-red-50 text-red-600 border border-red-200 text-xs font-medium px-2 py-1 rounded-full">Rejected</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.gym-applications.show', $app) }}" class="text-teal-600 hover:text-teal-700 font-medium">Review →</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-12 text-center text-gray-400 text-sm">No applications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($applications->hasPages())
    <div class="mt-6">
        {{ $applications->links() }}
    </div>
    @endif

</div>

</body>
</html>
