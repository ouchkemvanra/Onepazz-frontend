<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
        <a href="{{ route('gym-portal.index') }}" class="flex items-center gap-2 text-teal-600 font-bold text-lg">
            <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white text-sm">🏋️</div>
            OnePazz Partner
        </a>
        <div class="flex items-center gap-6">
            <a href="{{ route('gym-portal.index') }}"    class="text-sm {{ request()->routeIs('gym-portal.index')    ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Overview</a>
            <a href="{{ route('gym-portal.earnings') }}" class="text-sm {{ request()->routeIs('gym-portal.earnings') ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Earnings</a>
            <a href="{{ route('gym-portal.classes') }}"  class="text-sm {{ request()->routeIs('gym-portal.classes')  ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Classes</a>
            <a href="{{ route('gym-portal.bookings') }}" class="text-sm {{ request()->routeIs('gym-portal.bookings') ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Bookings</a>
            <a href="{{ route('gym-portal.reviews') }}"        class="text-sm {{ request()->routeIs('gym-portal.reviews')        ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Reviews</a>
            <a href="{{ route('gym-portal.staff.index') }}"   class="text-sm {{ request()->routeIs('gym-portal.staff*')         ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Staff</a>
            <a href="{{ route('gym-portal.qr-code') }}"       class="text-sm {{ request()->routeIs('gym-portal.qr-code*')       ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">QR Code</a>
            <a href="{{ route('gym-portal.checkin-screen') }}" class="text-sm {{ request()->routeIs('gym-portal.checkin-screen') ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Screen</a>
            <a href="{{ route('gym-portal.profile') }}"        class="text-sm {{ request()->routeIs('gym-portal.profile')        ? 'font-semibold text-teal-600' : 'text-gray-500 hover:text-gray-800' }}">Profile</a>
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
