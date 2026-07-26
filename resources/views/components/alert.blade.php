@props(['type' => 'success', 'message'])

@php
$classes = match($type) {
    'success' => 'bg-green-50 border-green-200 text-green-700',
    'error'   => 'bg-red-50 border-red-200 text-red-600',
    'warning' => 'bg-amber-50 border-amber-200 text-amber-700',
    default   => 'bg-brand-50 border-brand-200 text-brand-700',
};
$icon = match($type) {
    'success' => '✓',
    'error'   => '✕',
    'warning' => '⚠',
    default   => 'ℹ',
};
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 4000)"
    x-transition:enter="slide-in-right"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-x-0"
    x-transition:leave-end="opacity-0 translate-x-8"
    class="fixed bottom-6 right-6 z-50 max-w-sm w-full"
>
    <div class="flex items-start gap-3 border rounded-2xl px-4 py-3.5 shadow-lg {{ $classes }}">
        <span class="text-base font-bold shrink-0 mt-0.5">{{ $icon }}</span>
        <p class="text-sm font-medium flex-1">{{ $message }}</p>
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity ml-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>
