<nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="shrink-0">
                <x-logo variant="dark" size="40" />
            </a>

            {{-- Desktop nav --}}
            <div class="hidden md:flex items-center gap-1">
                <a href="{{ route('gyms.index') }}" class="{{ request()->routeIs('gyms.*') ? 'nav-link-active' : 'nav-link' }} px-3 py-2 rounded-xl hover:bg-gray-50">{{ __('nav.gyms') }}</a>
                <a href="{{ route('employer-register.create') }}" class="{{ request()->routeIs('employer-register.*') ? 'nav-link-active' : 'nav-link' }} px-3 py-2 rounded-xl hover:bg-gray-50">{{ __('nav.register_company') }}</a>
                <a href="{{ route('gym-apply.create') }}" class="{{ request()->routeIs('gym-apply.*') ? 'nav-link-active' : 'nav-link' }} px-3 py-2 rounded-xl hover:bg-gray-50">{{ __('nav.partner') }}</a>
            </div>

            {{-- Right side --}}
            <div class="hidden md:flex items-center gap-3">

                {{-- Language toggle --}}
                <div class="flex items-center bg-gray-100 rounded-xl p-0.5">
                    <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ App::getLocale() === 'en' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">EN</button>
                    </form>
                    <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium font-khmer rounded-lg transition-all {{ App::getLocale() === 'km' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">ខ្មែរ</button>
                    </form>
                </div>

                {{-- Currency toggle --}}
                <div class="flex items-center bg-gray-100 rounded-xl p-0.5">
                    <form method="POST" action="{{ route('currency.switch', 'usd') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ ($currency ?? 'usd') === 'usd' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">$ USD</button>
                    </form>
                    <form method="POST" action="{{ route('currency.switch', 'khr') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ ($currency ?? 'usd') === 'khr' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">៛ KHR</button>
                    </form>
                </div>

                @auth
                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 hover:bg-gray-50 rounded-xl px-2 py-1.5 transition-all">
                            <div class="w-8 h-8 rounded-xl bg-brand-500 text-white text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr(auth()->user()->full_name, 0, 2)) }}
                            </div>
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak class="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl border border-gray-100 shadow-card-hover py-1 z-50">
                            @if(auth()->user()->role === 'employer_admin')
                            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl mx-1">
                                <span>📊</span> Dashboard
                            </a>
                            @endif
                            <a href="{{ route('profile.index') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 rounded-xl mx-1">
                                <span>👤</span> {{ __('nav.profile') }}
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl mx-1 text-left">
                                    <span>🚪</span> {{ __('nav.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link px-3 py-2">{{ __('auth.sign_in') }}</a>
                    <a href="{{ route('employer-register.create') }}" class="btn-primary text-sm py-2 px-4">{{ __('home.get_started') }}</a>
                @endauth
            </div>

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="md:hidden btn-ghost p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile menu --}}
        <div x-show="mobileOpen" x-cloak class="md:hidden border-t border-gray-100 py-4 space-y-1">
            <a href="{{ route('gyms.index') }}" class="block nav-link px-3 py-2.5 rounded-xl hover:bg-gray-50">{{ __('nav.gyms') }}</a>
            <a href="{{ route('employer-register.create') }}" class="block nav-link px-3 py-2.5 rounded-xl hover:bg-gray-50">{{ __('nav.register_company') }}</a>
            <a href="{{ route('gym-apply.create') }}" class="block nav-link px-3 py-2.5 rounded-xl hover:bg-gray-50">{{ __('nav.partner') }}</a>
            <div class="pt-3 border-t border-gray-100 flex items-center gap-3 flex-wrap">
                <div class="flex items-center bg-gray-100 rounded-xl p-0.5">
                    <form method="POST" action="{{ route('language.switch', 'en') }}" class="inline">@csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ App::getLocale() === 'en' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500' }}">EN</button>
                    </form>
                    <form method="POST" action="{{ route('language.switch', 'km') }}" class="inline">@csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-khmer font-medium rounded-lg {{ App::getLocale() === 'km' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500' }}">ខ្មែរ</button>
                    </form>
                </div>
                <div class="flex items-center bg-gray-100 rounded-xl p-0.5">
                    <form method="POST" action="{{ route('currency.switch', 'usd') }}" class="inline">@csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ ($currency ?? 'usd') === 'usd' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500' }}">$ USD</button>
                    </form>
                    <form method="POST" action="{{ route('currency.switch', 'khr') }}" class="inline">@csrf
                        <button type="submit" class="px-3 py-1.5 text-xs font-medium rounded-lg {{ ($currency ?? 'usd') === 'khr' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500' }}">៛ KHR</button>
                    </form>
                </div>
            </div>
            @auth
                <div class="pt-2">
                    @if(auth()->user()->role === 'employer_admin')
                    <a href="{{ route('dashboard.index') }}" class="block nav-link px-3 py-2.5 rounded-xl hover:bg-gray-50">Dashboard</a>
                    @endif
                    <a href="{{ route('profile.index') }}" class="block nav-link px-3 py-2.5 rounded-xl hover:bg-gray-50">{{ __('nav.profile') }}</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="block w-full text-left text-sm text-red-500 px-3 py-2.5 rounded-xl hover:bg-red-50">{{ __('nav.logout') }}</button>
                    </form>
                </div>
            @else
                <div class="pt-2 flex gap-2">
                    <a href="{{ route('login') }}" class="btn-secondary text-sm flex-1 justify-center">{{ __('auth.sign_in') }}</a>
                    <a href="{{ route('employer-register.create') }}" class="btn-primary text-sm flex-1 justify-center">{{ __('home.get_started') }}</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
