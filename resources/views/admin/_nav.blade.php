<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="/" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏃</div>
            KhmerFit Admin
        </a>
        <div class="flex items-center gap-1">
            @php
                $navActive = fn($route) => request()->routeIs($route) ? 'text-teal-600 font-semibold' : 'text-gray-500 hover:text-gray-800';
            @endphp
            <a href="{{ route('admin.dashboard') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-gray-50 {{ $navActive('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.payments.index') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-gray-50 {{ $navActive('admin.payments.*') }}">Payments</a>

            {{-- Employers dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open"
                    class="text-sm px-3 py-2 rounded-lg hover:bg-gray-50 flex items-center gap-1
                    {{ request()->routeIs('admin.employers.*') ? 'text-teal-600 font-semibold' : 'text-gray-500' }}">
                    Employers
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="absolute top-full left-0 mt-1 w-52 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                    <a href="{{ route('admin.employers.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Employers</a>
                    <a href="{{ route('admin.employers.create') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Add New Employer</a>
                    <a href="{{ route('admin.employers.index', ['tab'=>'pending']) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pending Registrations</a>
                    <a href="{{ route('admin.employers.index', ['tab'=>'invitations']) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Invitations</a>
                </div>
            </div>

            {{-- Gyms dropdown --}}
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open"
                    class="text-sm px-3 py-2 rounded-lg hover:bg-gray-50 flex items-center gap-1
                    {{ request()->routeIs('admin.gyms.*') || request()->routeIs('admin.gym-applications.*') ? 'text-teal-600 font-semibold' : 'text-gray-500' }}">
                    Gyms
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-cloak class="absolute top-full left-0 mt-1 w-44 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                    <a href="{{ route('admin.gyms.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">All Gyms</a>
                    <a href="{{ route('admin.gyms.create') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Add New Gym</a>
                    <a href="{{ route('admin.gym-applications.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Applications</a>
                    <a href="{{ route('admin.gyms.invitations') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Invitations</a>
                </div>
            </div>

            <a href="{{ route('admin.payouts.index') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-gray-50 {{ $navActive('admin.payouts.*') }}">Payouts</a>
            <a href="{{ route('admin.settings') }}" class="text-sm px-3 py-2 rounded-lg hover:bg-gray-50 {{ $navActive('admin.settings') }}">Settings</a>
            <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                @csrf
                <button class="text-sm text-gray-500 hover:text-gray-800 px-2 py-1">Logout</button>
            </form>
            <div class="w-8 h-8 rounded-full bg-teal-600 text-white text-xs flex items-center justify-center font-semibold ml-2">
                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
            </div>
        </div>
    </div>
</nav>
<style>[x-cloak]{display:none!important;}</style>
