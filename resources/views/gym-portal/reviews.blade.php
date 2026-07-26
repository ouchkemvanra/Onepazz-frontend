<x-layouts.gym-portal title="Reviews">

<div class="mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Reviews</h1>
    <p class="text-sm text-gray-400 mt-1">{{ $reviews->count() }} total review{{ $reviews->count() !== 1 ? 's' : '' }}</p>
</div>

{{-- Summary card --}}
<div class="card p-6 mb-6">
    <div class="flex items-center gap-10">
        <div class="text-center shrink-0">
            <p class="text-5xl font-bold text-gray-900">{{ number_format($gym->average_rating, 1) }}</p>
            <div class="flex justify-center gap-0.5 mt-2">
                @for($i = 1; $i <= 5; $i++)
                <span class="text-xl {{ $i <= round($gym->average_rating) ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                @endfor
            </div>
            <p class="text-xs text-gray-400 mt-1">{{ $gym->review_count }} reviews</p>
        </div>
        <div class="flex-1">
            @foreach($breakdown as $row)
            <div class="flex items-center gap-3 mb-1.5">
                <span class="text-sm text-gray-400 w-4 shrink-0">{{ $row['stars'] }}★</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2">
                    <div class="bg-amber-400 h-2 rounded-full transition-all" style="width:{{ $row['percent'] }}%"></div>
                </div>
                <span class="text-xs text-gray-400 w-8 text-right">{{ $row['percent'] }}%</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Reviews list --}}
<div class="space-y-4">
    @forelse($reviews as $review)
    <div class="card p-5">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-brand-100 text-brand-700 text-sm font-bold flex items-center justify-center shrink-0">
                    {{ strtoupper(substr($review->user->full_name ?? 'U', 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800">
                        {{ strtoupper(substr($review->user->full_name ?? 'User', 0, 1)) }}. {{ strtoupper(substr(strrchr($review->user->full_name ?? 'User', ' ') ?: $review->user->full_name, 1, 1)) }}.
                    </p>
                    <div class="flex gap-0.5 mt-0.5">
                        @for($i = 1; $i <= 5; $i++)
                        <span class="text-sm {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}">★</span>
                        @endfor
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
        </div>
        @if($review->comment)
        <p class="text-sm text-gray-600 leading-relaxed">{{ $review->comment }}</p>
        @endif
    </div>
    @empty
    <div class="text-center py-16">
        <div class="text-5xl mb-4">⭐</div>
        <p class="text-gray-500 font-medium">No reviews yet</p>
        <p class="text-gray-400 text-sm mt-1">Reviews from members will appear here.</p>
    </div>
    @endforelse
</div>

</x-layouts.gym-portal>
