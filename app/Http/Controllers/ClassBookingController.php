<?php

namespace App\Http\Controllers;

use App\Models\ClassBooking;
use App\Models\Gym;
use App\Models\GymClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClassBookingController extends Controller
{
    public function book(Gym $gym, GymClass $class)
    {
        abort_if($class->gym_id !== $gym->id, 404);

        if ($gym->hasReachedDailyLimit()) {
            return back()->withErrors(['booking' => 'Daily capacity reached for this gym.']);
        }

        $existing = ClassBooking::where('gym_class_id', $class->id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['confirmed', 'waitlisted'])
            ->exists();

        if ($existing) {
            return back()->withErrors(['booking' => 'You have already booked this class.']);
        }

        $confirmedCount = $class->confirmedCount();
        $status         = $confirmedCount < $class->max_capacity ? 'confirmed' : 'waitlisted';

        ClassBooking::create([
            'gym_class_id' => $class->id,
            'user_id'      => auth()->id(),
            'status'       => $status,
            'booked_at'    => now(),
        ]);

        $message = $status === 'confirmed'
            ? 'Booking confirmed!'
            : 'Class is full — you have been added to the waitlist.';

        return back()->with('success', $message);
    }

    public function cancel(Gym $gym, GymClass $class)
    {
        abort_if($class->gym_id !== $gym->id, 404);

        $booking = ClassBooking::where('gym_class_id', $class->id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['confirmed', 'waitlisted'])
            ->firstOrFail();

        $wasConfirmed = $booking->status === 'confirmed';
        $booking->update(['status' => 'cancelled']);

        // Promote first waitlisted if a confirmed spot opened
        if ($wasConfirmed) {
            $next = ClassBooking::where('gym_class_id', $class->id)
                ->where('status', 'waitlisted')
                ->oldest('booked_at')
                ->first();

            if ($next) {
                $next->update(['status' => 'confirmed', 'notified_at' => now()]);
                Mail::to($next->user->email)->send(new \App\Mail\ClassBookingPromotedMail($next));
            }
        }

        return back()->with('success', 'Booking cancelled.');
    }
}
