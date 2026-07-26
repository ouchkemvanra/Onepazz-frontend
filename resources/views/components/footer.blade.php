<footer class="bg-gray-900 text-gray-400 mt-20">
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">

            {{-- Brand --}}
            <div class="md:col-span-1">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                    <x-logo variant="white" size="32" />
                    <span class="font-bold text-white text-lg">OnePazz</span>
                </a>
                <p class="text-sm leading-relaxed text-gray-500">Cambodia's Corporate Wellness Platform. Connecting teams with the best gyms across the Kingdom.</p>
            </div>

            {{-- Platform --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-4">Platform</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('gyms.index') }}" class="text-sm hover:text-white transition-colors">Browse Gyms</a></li>
                    <li><a href="{{ route('employer-register.create') }}" class="text-sm hover:text-white transition-colors">For Employers</a></li>
                    <li><a href="{{ route('gym-apply.create') }}" class="text-sm hover:text-white transition-colors">Partner with Us</a></li>
                    <li><a href="#plans" class="text-sm hover:text-white transition-colors">Pricing</a></li>
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-4">Company</h4>
                <ul class="space-y-2.5">
                    <li><a href="#" class="text-sm hover:text-white transition-colors">About Us</a></li>
                    <li><a href="#" class="text-sm hover:text-white transition-colors">Contact</a></li>
                    <li><a href="#" class="text-sm hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('gym-apply.terms') }}" class="text-sm hover:text-white transition-colors">Terms of Service</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-white font-semibold text-sm mb-4">Contact</h4>
                <ul class="space-y-2.5">
                    <li class="text-sm flex items-center gap-2"><span>📧</span> hello@onepazz.com</li>
                    <li class="text-sm flex items-center gap-2"><span>📱</span> +855 23 000 000</li>
                    <li class="text-sm flex items-center gap-2"><span>📍</span> Phnom Penh, Cambodia</li>
                </ul>
                {{-- Social --}}
                <div class="flex items-center gap-3 mt-5">
                    <a href="#" class="w-8 h-8 bg-gray-800 hover:bg-brand-600 rounded-lg flex items-center justify-center text-sm transition-colors">f</a>
                    <a href="#" class="w-8 h-8 bg-gray-800 hover:bg-brand-600 rounded-lg flex items-center justify-center text-sm transition-colors">in</a>
                    <a href="#" class="w-8 h-8 bg-gray-800 hover:bg-brand-600 rounded-lg flex items-center justify-center text-sm transition-colors">t</a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-xs text-gray-600">© 2026 OnePazz Co., Ltd · Phnom Penh, Cambodia</p>
            <p class="text-xs text-gray-600">🇰🇭 Built for Cambodia · បង្កើតសម្រាប់កម្ពុជា</p>
        </div>
    </div>
</footer>
