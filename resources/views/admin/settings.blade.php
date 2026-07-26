<x-layouts.admin title="Settings">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Platform Settings</h1>
    <p class="text-sm text-gray-400 mt-1">Configure platform-wide settings</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif

{{-- Exchange rate --}}
<div class="card p-6 mb-5">
    <h3 class="font-bold text-gray-900 mb-1">Currency Exchange Rate</h3>
    <p class="text-sm text-gray-500 mb-5">Set the USD to KHR exchange rate used for invoice calculations.</p>
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        <div class="mb-5">
            <label class="label">USD to KHR Rate</label>
            <div class="flex items-center gap-3">
                <span class="text-gray-400 text-sm">1 USD =</span>
                <input type="number" name="khr_rate" value="{{ old('khr_rate', $khrRate) }}"
                    step="0.01" min="1" max="50000" required
                    class="input w-32 text-right font-mono">
                <span class="text-gray-400 text-sm">KHR</span>
            </div>
            <p class="text-xs text-gray-400 mt-1">Current: 1 USD = {{ number_format($khrRate) }} KHR</p>
        </div>
        <button type="submit" class="btn-primary text-sm">Save Exchange Rate</button>
    </form>
</div>

{{-- Check-in radius --}}
<div class="card p-6 mb-5">
    <h3 class="font-bold text-gray-900 mb-1">Check-in Radius</h3>
    <p class="text-sm text-gray-500 mb-5">Default radius used when a gym doesn't have a custom setting.</p>
    <form method="POST" action="{{ route('admin.settings.checkin-radius') }}">
        @csrf
        <div class="flex items-center gap-3 mb-4">
            <label class="label m-0">Default Radius</label>
            <input type="number" name="checkin_radius_default"
                value="{{ old('checkin_radius_default', $checkinRadiusDefault) }}"
                min="10" max="5000" class="input w-28 font-mono">
            <span class="text-gray-400 text-sm">meters</span>
        </div>
        <button type="submit" class="btn-primary text-sm">Save Radius</button>
    </form>
</div>

{{-- Revenue share --}}
<div class="card p-6 mb-5">
    <h3 class="font-bold text-gray-900 mb-1">Revenue Share Settings</h3>
    <p class="text-sm text-gray-500 mb-5">Configure check-ins per payout unit by tier and the default OnePazz cut.</p>
    <form method="POST" action="{{ route('admin.settings.revenue-config') }}">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div>
                <label class="label">Check-ins per unit — Gold</label>
                <input type="number" name="checkins_per_unit_gold" value="{{ old('checkins_per_unit_gold', $checkinsGold) }}" min="1" required class="input w-32 font-mono">
            </div>
            <div>
                <label class="label">Check-ins per unit — Silver</label>
                <input type="number" name="checkins_per_unit_silver" value="{{ old('checkins_per_unit_silver', $checkinsSilver) }}" min="1" required class="input w-32 font-mono">
            </div>
            <div>
                <label class="label">Check-ins per unit — Bronze</label>
                <input type="number" name="checkins_per_unit_bronze" value="{{ old('checkins_per_unit_bronze', $checkinsBronze) }}" min="1" required class="input w-32 font-mono">
            </div>
            <div>
                <label class="label">Default OnePazz Cut (%)</label>
                <div class="flex items-center gap-2">
                    <input type="number" name="revenue_share_pct_default" value="{{ old('revenue_share_pct_default', $revenueShareDefault) }}" min="0" max="100" step="0.01" required class="input w-28 font-mono">
                    <span class="text-gray-400 text-sm">%</span>
                </div>
            </div>
        </div>
        <button type="submit" class="btn-primary text-sm">Save Revenue Config</button>
    </form>
</div>

<div class="bg-brand-50 border border-brand-200 rounded-2xl p-4">
    <p class="text-sm text-brand-700">
        <strong>Note:</strong> Exchange rate changes apply to new invoices only. Existing invoices retain their original rate.
    </p>
</div>

</x-layouts.admin>
