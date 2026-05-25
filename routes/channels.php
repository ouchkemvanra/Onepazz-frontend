<?php

use App\Models\GymStaff;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('gym.{gymId}', function ($user, $gymId) {
    if ($user->isGymAdmin() && $user->adminForGym?->id == $gymId) {
        return true;
    }
    return GymStaff::where('user_id', $user->id)
        ->where('gym_id', $gymId)
        ->where('is_active', true)
        ->exists();
});
