<x-layouts.gym-portal title="QR Check-in Code">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">QR Check-in Code</h1>
    <p class="text-sm text-gray-400 mt-1">Post this at your entrance for member self check-in</p>
</div>

{{-- QR display --}}
<div class="card p-8 text-center mb-6 max-w-sm">
    <div class="inline-block p-5 bg-white rounded-2xl shadow-card border border-gray-100 mb-4">
        {!! $qrSvg !!}
    </div>
    <p class="text-xs font-mono text-gray-400 mt-1">{{ $gym->qr_code }}</p>
    <p class="text-sm font-semibold text-gray-700 mt-2">{{ $gym->name }}</p>
</div>

{{-- Instructions --}}
<div class="bg-brand-50 border border-brand-200 rounded-2xl p-5 mb-6 max-w-sm">
    <h3 class="font-bold text-brand-800 mb-2">Print Instructions</h3>
    <p class="text-sm text-brand-700 leading-relaxed">
        Post this QR code at your entrance. Members scan it with the OnePazz app to check in automatically.
        Members must be within <strong>{{ $gym->checkin_radius_meters ?? 50 }}m</strong> of your gym.
    </p>
</div>

{{-- Actions --}}
<div class="flex gap-3 flex-wrap max-w-sm">
    <a href="{{ route('gym-portal.qr-code') }}?download=1" class="btn-primary text-sm">
        📥 Download PNG
    </a>
    <button onclick="window.print()" class="btn-secondary text-sm">
        🖨️ Print
    </button>
    <form method="POST" action="{{ route('gym-portal.qr-code.regenerate') }}"
          onsubmit="return confirm('Regenerate QR code? The current QR will stop working immediately.')">
        @csrf
        <button type="submit" class="btn-danger text-sm">
            Regenerate QR
        </button>
    </form>
</div>

<p class="text-xs text-gray-400 mt-4">Current check-in radius: <strong>{{ $gym->checkin_radius_meters ?? 50 }}m</strong>. Adjust in Admin → Gyms.</p>

</x-layouts.gym-portal>
