<x-layouts.admin title="Edit {{ $gym->name }}">

<a href="{{ route('admin.gyms.index') }}" class="inline-flex items-center gap-1.5 text-brand-600 hover:text-brand-700 text-sm mb-6 font-medium">
    ← Back to Gyms
</a>

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">{{ $gym->name }}</h1>
    <p class="text-sm text-gray-400 mt-1">Partner settings</p>
</div>

@if($errors->any())
<div class="bg-red-50 border border-red-200 text-red-600 rounded-2xl px-5 py-4 mb-6">
    <ul class="list-disc list-inside space-y-1 text-sm">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="max-w-2xl">
    <form method="POST" action="{{ route('admin.gyms.update', $gym) }}">
        @csrf @method('PATCH')
        <div class="card p-6 space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">Tier</label>
                    <select name="tier" class="input">
                        @foreach(['bronze','silver','gold'] as $t)
                        <option value="{{ $t }}" {{ old('tier', $gym->tier) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Monthly Fee (USD)</label>
                    <input type="number" step="0.01" min="0" name="monthly_fee_usd" value="{{ old('monthly_fee_usd', $gym->monthly_fee_usd) }}" class="input font-mono">
                </div>
                <div>
                    <label class="label">OnePazz Cut % (override)</label>
                    <input type="number" step="0.01" min="0" max="100" name="revenue_share_pct" value="{{ old('revenue_share_pct', $gym->revenue_share_pct) }}" class="input font-mono">
                    <p class="text-xs text-gray-400 mt-1">Platform default is {{ \App\Models\PlatformConfig::get('revenue_share_pct_default', 30) }}%</p>
                </div>
                <div>
                    <label class="label">Daily Capacity Limit</label>
                    <input type="number" min="1" name="daily_capacity_limit" value="{{ old('daily_capacity_limit', $gym->daily_capacity_limit) }}" placeholder="Unlimited" class="input font-mono">
                </div>
                <div>
                    <label class="label">Check-in Radius (meters)</label>
                    <input type="number" min="10" max="5000" name="checkin_radius_meters" value="{{ old('checkin_radius_meters', $gym->checkin_radius_meters ?? 50) }}" class="input font-mono">
                    <p class="text-xs text-gray-400 mt-1">Members must be within this distance to QR check in.</p>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-5">
                <label class="label">QR Token</label>
                <div class="flex items-center gap-3">
                    <input type="text" readonly value="{{ $gym->qr_code ?? 'Not generated' }}" class="flex-1 input bg-gray-50 font-mono text-gray-500 text-xs">
                    <form method="POST" action="{{ route('admin.gyms.regenerate-qr', $gym) }}">
                        @csrf
                        <button type="submit" class="btn-secondary text-sm whitespace-nowrap">Regenerate</button>
                    </form>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Save Changes</button>
                <a href="{{ route('admin.gyms.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>

</x-layouts.admin>
