<x-layouts.app title="Cambodia's Corporate Wellness Platform">

{{-- HERO --}}
<section class="relative bg-gradient-to-br from-brand-600 via-brand-500 to-warm-500 text-white overflow-hidden">
    {{-- Decorative blobs --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 -left-16 w-80 h-80 bg-warm-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-6 pt-20 pb-16 text-center">
        <div class="inline-flex items-center gap-2 bg-white/15 border border-white/25 text-white/90 text-xs font-medium px-4 py-1.5 rounded-full mb-6 backdrop-blur-sm">
            🇰🇭 Built for Cambodia · បង្កើតសម្រាប់កម្ពុជា
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight mb-5 leading-tight">
            Corporate Wellness,<br><span class="text-white/80">Reimagined</span>
        </h1>
        <p class="text-white/75 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            Connect your team with Cambodia's best gyms and fitness studios. One platform, hundreds of wellness options.
        </p>
        <div class="flex gap-3 justify-center flex-wrap">
            <a href="{{ route('employer-register.create') }}"
               class="inline-flex items-center gap-2 bg-white text-brand-600 font-semibold px-7 py-3.5 rounded-2xl hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                {{ __('home.get_started') }} →
            </a>
            <a href="{{ route('gyms.index') }}"
               class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-medium px-7 py-3.5 rounded-2xl border border-white/25 transition-all backdrop-blur-sm">
                {{ __('home.browse_gyms') }}
            </a>
        </div>

        {{-- Stats bar --}}
        <div class="flex flex-wrap justify-center gap-8 md:gap-16 mt-16 pt-12 border-t border-white/15">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold" data-counter="120">0</div>
                <div class="text-xs text-white/60 mt-1 font-medium uppercase tracking-wide">Partner Gyms</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold" data-counter="48">0</div>
                <div class="text-xs text-white/60 mt-1 font-medium uppercase tracking-wide">Corporate Partners</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold" data-counter="5200">0</div>
                <div class="text-xs text-white/60 mt-1 font-medium uppercase tracking-wide">Active Members</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-bold" data-counter="7">0</div>
                <div class="text-xs text-white/60 mt-1 font-medium uppercase tracking-wide">Provinces</div>
            </div>
        </div>
    </div>
</section>

{{-- TRUST BAR --}}
<section class="bg-white border-b border-gray-100 py-5">
    <div class="max-w-5xl mx-auto px-6">
        <div class="flex flex-wrap items-center justify-center gap-6 md:gap-12 text-gray-400 text-sm">
            <div class="flex items-center gap-2"><span class="text-brand-500">✓</span> Bank transfer payments</div>
            <div class="flex items-center gap-2"><span class="text-brand-500">✓</span> KHR &amp; USD pricing</div>
            <div class="flex items-center gap-2"><span class="text-brand-500">✓</span> No app required</div>
            <div class="flex items-center gap-2"><span class="text-brand-500">✓</span> 24h onboarding</div>
            <div class="flex items-center gap-2"><span class="text-brand-500">✓</span> Enterprise ready</div>
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="max-w-5xl mx-auto px-6 py-20">
    <div class="text-center mb-14">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">How it works</h2>
        <p class="text-gray-500 text-lg">Three steps to company-wide wellness</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach([
            ['1', '🏢', 'Register your company', 'Sign up your organisation, choose a wellness plan, and complete bank transfer payment. Verified within 24 hours.'],
            ['2', '👥', 'Invite your employees', 'Upload your staff list or invite individually. Each employee gets a membership card and full platform access.'],
            ['3', '📊', 'Track & report', 'Monitor usage, generate CSV or PDF reports, and manage access from your dashboard — in KHR and USD.'],
        ] as [$num, $icon, $title, $desc])
        <div class="card p-7 group hover:-translate-y-1 transition-transform duration-200">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-brand-50 flex items-center justify-center text-xl">{{ $icon }}</div>
                <span class="text-xs font-bold text-brand-500 uppercase tracking-widest">Step {{ $num }}</span>
            </div>
            <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- PLANS --}}
<section id="plans" class="bg-gray-50 py-20 px-6">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Corporate Plans</h2>
            <p class="text-gray-500 text-lg">Dual KHR / USD pricing · Payment via bank transfer</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($plans as $plan)
            <div class="relative card p-8 flex flex-col {{ $plan->tier === 'silver' ? 'ring-2 ring-brand-500 shadow-card-hover' : '' }}">
                @if($plan->tier === 'silver')
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 bg-brand-500 text-white text-xs font-bold px-4 py-1 rounded-full shadow-sm">
                        ⭐ Most Popular
                    </div>
                @endif
                <div class="mb-6">
                    @if($plan->tier === 'all')
                        <span class="badge-gold">Gold</span>
                    @elseif($plan->tier === 'silver')
                        <span class="badge-silver">Silver</span>
                    @else
                        <span class="badge-bronze">Bronze</span>
                    @endif
                    <div class="mt-4 text-4xl font-bold text-gray-900">{{ format_currency($plan->price_usd) }}</div>
                    <div class="text-sm text-gray-400 mt-1">{{ __('common.per_month') }}</div>
                </div>
                <ul class="space-y-2.5 flex-1 mb-8">
                    @foreach($plan->features ?? [] as $feature)
                    <li class="text-sm text-gray-600 flex items-start gap-2.5">
                        <span class="text-brand-500 font-bold mt-0.5 shrink-0">✓</span>
                        <span>{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                <a href="{{ route('employer-register.create') }}"
                   class="{{ $plan->tier === 'silver' ? 'btn-primary' : 'btn-secondary' }} w-full justify-center">
                    Get {{ $plan->name }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURED GYMS --}}
<section id="gyms" class="max-w-5xl mx-auto px-6 py-20">
    <div class="text-center mb-14">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">Featured Gyms</h2>
        <p class="text-gray-500 text-lg">A sample of our Phnom Penh network</p>
    </div>
    @if($featuredGyms->isEmpty())
    <div class="text-center py-16 text-gray-400">
        <div class="text-5xl mb-4">🏋️</div>
        <p class="text-lg font-medium">Gyms coming soon</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($featuredGyms as $gym)
        <a href="{{ route('gyms.show', $gym) }}" class="card card-hover overflow-hidden group flex flex-col">
            <div class="h-40 bg-gradient-to-br from-brand-50 to-brand-100 flex items-center justify-center text-5xl relative">
                {{ match(true) {
                    in_array('yoga', $gym->activity_types ?? [])      => '🧘',
                    in_array('kun_khmer', $gym->activity_types ?? []) => '🥊',
                    in_array('swimming', $gym->activity_types ?? [])  => '🏊',
                    default => '🏋️'
                } }}
                <div class="absolute top-3 right-3">
                    @if($gym->tier === 'gold')
                        <span class="badge-gold">Gold</span>
                    @elseif($gym->tier === 'silver')
                        <span class="badge-silver">Silver</span>
                    @else
                        <span class="badge-bronze">Bronze</span>
                    @endif
                </div>
            </div>
            <div class="p-5 flex-1">
                <h3 class="font-bold text-gray-900 mb-1 group-hover:text-brand-600 transition-colors">{{ $gym->name }}</h3>
                <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                    <span>📍</span> {{ $gym->district }}, {{ $gym->city }}
                </p>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    @foreach(array_slice($gym->activity_types ?? [], 0, 3) as $tag)
                    <span class="badge badge-gray text-xs">{{ ucwords(str_replace('_', ' ', $tag)) }}</span>
                    @endforeach
                </div>
                <div class="flex items-center gap-1 text-xs text-gray-400">
                    <span class="text-amber-400 text-sm">★</span>
                    <span class="font-semibold text-gray-700">{{ number_format($gym->average_rating, 1) }}</span>
                    <span>({{ $gym->review_count }} reviews)</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
    <div class="text-center mt-10">
        <a href="{{ route('gyms.index') }}" class="btn-secondary">
            View all gyms →
        </a>
    </div>
    @endif
</section>

{{-- CTA BANNER --}}
<section class="bg-gradient-to-r from-brand-500 to-brand-600 py-16 px-6">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Ready to get started?</h2>
        <p class="text-white/75 text-lg mb-8">Join the growing number of Cambodian companies investing in employee wellness.</p>
        <div class="flex gap-3 justify-center flex-wrap">
            <a href="{{ route('employer-register.create') }}"
               class="inline-flex items-center gap-2 bg-white text-brand-600 font-bold px-8 py-4 rounded-2xl hover:bg-gray-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                Register your company →
            </a>
            <a href="{{ route('gym-apply.create') }}"
               class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 text-white font-semibold px-8 py-4 rounded-2xl border border-white/25 transition-all">
                Partner your gym
            </a>
        </div>
    </div>
</section>

</x-layouts.app>
