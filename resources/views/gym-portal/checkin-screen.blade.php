<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Check-in Screen — {{ $gym->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <style>
        .fade-out { animation: fadeout 30s forwards; }
        @keyframes fadeout { 0%,90%{opacity:1} 100%{opacity:.3} }
    </style>
</head>
<body class="bg-gray-950 text-white min-h-screen font-sans">

{{-- Header --}}
<div class="bg-gray-900 border-b border-gray-800 px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <x-logo variant="white" size="36" />
        <div>
            <p class="font-bold text-white">{{ $gym->name }}</p>
            <p class="text-xs text-gray-400">{{ $staffRole }}: {{ auth()->user()->full_name }}</p>
        </div>
    </div>
    <div class="text-right">
        <p class="text-sm font-mono text-gray-300" id="clock">{{ now()->format('H:i') }}</p>
        <p class="text-xs text-gray-500">{{ now()->format('D, d M Y') }}</p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-6 py-6">

    {{-- Latest check-in card --}}
    <div class="mb-6">
        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">Latest Check-in</p>
        <div id="latest-card" class="bg-gray-900 rounded-2xl border border-gray-800 p-6 min-h-[120px] flex items-center justify-center">
            <div class="text-center">
                <div class="text-3xl mb-2">⏳</div>
                <p class="text-gray-500 text-sm">Waiting for check-ins...</p>
            </div>
        </div>
    </div>

    {{-- Today's feed --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Today's Check-ins</p>
            <span id="total-count" class="text-xs bg-gray-800 border border-gray-700 px-3 py-1 rounded-full text-gray-400 font-mono">
                {{ $todayCheckins->count() }} total
            </span>
        </div>
        <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
            <div id="live-feed" class="divide-y divide-gray-800 max-h-96 overflow-y-auto">
                @forelse($todayCheckins as $ci)
                <div class="flex items-center gap-3 px-5 py-3">
                    <span class="text-lg">✅</span>
                    <span class="text-sm text-gray-500 w-14 shrink-0 font-mono">{{ $ci->checked_in_at->format('H:i') }}</span>
                    <span class="font-mono text-sm text-white font-semibold">{{ $ci->employee?->membership_card_no ?? '—' }}</span>
                    <span class="text-xs text-gray-500 ml-auto">{{ $ci->employee?->subscription?->plan?->name ?? '—' }}</span>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-gray-600 text-sm" id="empty-msg">No check-ins yet today.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
setInterval(() => {
    const now = new Date();
    document.getElementById('clock').textContent =
        String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
}, 10000);

const pusher = new Pusher('{{ config("broadcasting.connections.reverb.key") }}', {
    wsHost:    '{{ config("broadcasting.connections.reverb.options.host") }}',
    wsPort:    {{ config("broadcasting.connections.reverb.options.port", 8080) }},
    wssPort:   {{ config("broadcasting.connections.reverb.options.port", 8080) }},
    forceTLS:  false,
    enabledTransports: ['ws'],
    cluster:   'mt1',
    channelAuthorization: {
        endpoint:  '/broadcasting/auth',
        transport: 'ajax',
        headers:   { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
    },
});

const channel = pusher.subscribe('private-gym.{{ $gym->id }}');
channel.bind('member.checkin', (data) => {
    updateLatestCard(data);
    addToFeed(data);
});

let feedCount = {{ $todayCheckins->count() }};

function updateLatestCard(data) {
    const approved = data.status === 'approved';
    const card = document.getElementById('latest-card');
    card.className = `rounded-2xl border p-6 fade-out ${approved ? 'bg-green-950 border-green-800' : 'bg-red-950 border-red-800'}`;
    card.innerHTML = `
        <div class="w-full">
            <div class="flex items-center justify-between mb-4">
                <span class="text-xl font-bold ${approved ? 'text-green-400' : 'text-red-400'}">
                    ${approved ? '✅ CHECK-IN APPROVED' : '❌ CHECK-IN DENIED'}
                </span>
                <span class="text-sm text-gray-500 font-mono">${data.checked_in_at}</span>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><span class="text-gray-500">Card: </span><span class="font-mono font-bold text-white">${data.card_no}</span></div>
                <div><span class="text-gray-500">Plan: </span><span class="text-white">${data.plan_name}</span></div>
                ${approved ? `
                <div><span class="text-gray-500">Visits: </span><span class="text-white">${data.visits_this_month} / ${data.monthly_limit} this month</span></div>
                <div><span class="text-gray-500">Gym today: </span><span class="text-white">${data.gym_capacity_today}${data.gym_daily_limit ? ' / ' + data.gym_daily_limit : ''}</span></div>
                ` : `<div class="col-span-2"><span class="text-gray-500">Reason: </span><span class="text-red-300">${data.reason}</span></div>`}
            </div>
        </div>`;
    void card.offsetWidth;
    card.classList.add('fade-out');
}

function addToFeed(data) {
    const feed = document.getElementById('live-feed');
    const empty = document.getElementById('empty-msg');
    if (empty) empty.remove();
    feedCount++;
    document.getElementById('total-count').textContent = `${feedCount} total`;
    const icon = data.status === 'approved' ? '✅' : '❌';
    const row = document.createElement('div');
    row.className = 'flex items-center gap-3 px-5 py-3 border-b border-gray-800';
    row.innerHTML = `
        <span class="text-lg">${icon}</span>
        <span class="text-sm text-gray-500 w-14 shrink-0 font-mono">${data.checked_in_at}</span>
        <span class="font-mono text-sm font-bold text-white">${data.card_no}</span>
        <span class="text-xs text-gray-500 ml-auto">${data.reason ?? data.plan_name}</span>`;
    feed.insertBefore(row, feed.firstChild);
    while (feed.children.length > 50) feed.removeChild(feed.lastChild);
}
</script>
</body>
</html>
