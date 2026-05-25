<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberCheckedIn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int     $gymId,
        public string  $cardNo,
        public string  $planName,
        public int     $visitsThisMonth,
        public int     $monthlyLimit,
        public int     $gymCapacityToday,
        public ?int    $gymDailyLimit,
        public string  $status,
        public ?string $reason,
        public string  $checkedInAt,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('gym.' . $this->gymId)];
    }

    public function broadcastAs(): string
    {
        return 'member.checkin';
    }
}
