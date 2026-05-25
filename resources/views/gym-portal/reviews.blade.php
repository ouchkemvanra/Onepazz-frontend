<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews — {{ $gym->name }} — KhmerFit Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300..700&display=swap" rel="stylesheet">
    <style>body{font-family:'DM Sans',sans-serif;}</style>
</head>
<body class="bg-gray-50">

@include('gym-portal._nav')

<div class="max-w-5xl mx-auto px-6 py-8">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Reviews</h1>
        <p class="text-sm text-gray-400 mt-1">{{ $reviews->count() }} total review{{ $reviews->count() !== 1 ? 's' : '' }}</p>
    </div>

    {{-- Summary --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-8">
            <div class="text-center">
                <p class="text-5xl font-bold text-gray-800">{{ number_format($gym->average_rating, 1) }}</p>
                <div class="flex justify-center gap-0.5 mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <span class="text-lg {{ $i <= round($gym->average_rating) ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                    @endfor
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $gym->review_count }} reviews</p>
            </div>
            <div class="flex-1">
                @foreach($breakdown as $row)
                <div class="flex items-center gap-3 mb-1">
                    <span class="text-sm text-gray-500 w-4">{{ $row['stars'] }}★</span>
                    <div class="flex-1 bg-gray-100 rounded-full h-2">
                        <div class="bg-yellow-400 h-2 rounded-full" style="width:{{ $row['percent'] }}%"></div>
                    </div>
                    <span class="text-xs text-gray-400 w-8 text-right">{{ $row['percent'] }}%</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Reviews List --}}
    <div class="space-y-4">
        @forelse($reviews as $review)
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-teal-100 text-teal-700 text-sm font-bold flex items-center justify-center">
                        {{ strtoupper(substr($review->user->full_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(explode(' ', $review->user->full_name ?? 'U')[1] ?? 'X', 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            {{ strtoupper(substr($review->user->full_name ?? 'User', 0, 1)) }}. {{ strtoupper(substr(strrchr($review->user->full_name ?? 'User', ' ') ?: $review->user->full_name, 1, 1)) }}.
                        </p>
                        <div class="flex gap-0.5">
                            @for($i = 1; $i <= 5; $i++)
                            <span class="text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}">★</span>
                            @endfor
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</p>
            </div>
            @if($review->comment)
            <p class="text-sm text-gray-600 mt-3">{{ $review->comment }}</p>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
            <p class="text-gray-400 text-sm">No reviews yet.</p>
        </div>
        @endforelse
    </div>
</div>
</body>
</html>
