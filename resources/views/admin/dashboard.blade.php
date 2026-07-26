<x-layouts.admin title="Overview">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
    <p class="text-sm text-gray-400 mt-1">Platform administration and management</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <a href="{{ route('admin.payments.index') }}" class="card card-hover p-6 group">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pending Payments</p>
        <p class="text-4xl font-bold text-warm-500">{{ $stats['pending_payments'] }}</p>
        <p class="text-xs text-brand-500 mt-2 font-medium group-hover:translate-x-1 transition-transform inline-block">→ Review payments</p>
    </a>
    <a href="{{ route('admin.gym-applications.index') }}" class="card card-hover p-6 group">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pending Gym Applications</p>
        <p class="text-4xl font-bold text-warm-500">{{ $stats['pending_gym_applications'] }}</p>
        <p class="text-xs text-brand-500 mt-2 font-medium group-hover:translate-x-1 transition-transform inline-block">→ Review applications</p>
    </a>
</div>

<div class="card p-6">
    <h3 class="font-bold text-gray-900 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
        @foreach([
            [route('admin.payments.index'), '💳', 'Manage Payments'],
            [route('admin.gym-applications.index'), '🏋️', 'Gym Applications'],
            [route('admin.gyms.index'), '🏢', 'Manage Gyms'],
            [route('admin.employers.index'), '👔', 'Employers'],
            [route('admin.payouts.index'), '💰', 'Payouts'],
            [route('admin.settings'), '⚙️', 'Settings'],
        ] as [$url, $icon, $label])
        <a href="{{ $url }}" class="flex items-center gap-2.5 border border-gray-100 bg-gray-50 hover:bg-brand-50 hover:border-brand-200 px-4 py-3 rounded-xl transition-all text-sm font-medium text-gray-700 hover:text-brand-700">
            <span class="text-base">{{ $icon }}</span> {{ $label }}
        </a>
        @endforeach
    </div>
</div>

</x-layouts.admin>
