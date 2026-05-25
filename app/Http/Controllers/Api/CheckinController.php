<?php

namespace App\Http\Controllers\Api;

use App\Events\MemberCheckedIn;
use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Employee;
use App\Models\Gym;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'qr_token'    => 'required|string',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'member_card' => 'required|string',
        ]);

        $gym = Gym::where('qr_code', $request->qr_token)->where('status', 'active')->first();
        if (!$gym) {
            return $this->deny($request, null, 404, 'Invalid QR code');
        }

        if (!$gym->isWithinRadius((float) $request->latitude, (float) $request->longitude)) {
            return $this->deny($request, $gym, 422, 'You must be at the gym to check in');
        }

        $employee = Employee::where('membership_card_no', $request->member_card)
            ->with(['subscription.plan'])
            ->first();

        if (!$employee) {
            return $this->deny($request, $gym, 404, 'Membership card not found');
        }

        if ($employee->status !== 'active') {
            return $this->deny($request, $gym, 403, 'Membership suspended', $employee);
        }

        if (!$employee->subscription || $employee->subscription->status !== 'active') {
            return $this->deny($request, $gym, 403, 'No active subscription', $employee);
        }

        if ($employee->hasReachedMonthlyLimit()) {
            return $this->deny($request, $gym, 403, 'Monthly visit limit reached', $employee);
        }

        if ($gym->hasReachedDailyLimit()) {
            return $this->deny($request, $gym, 403, 'Gym has reached daily capacity', $employee);
        }

        $checkin = Checkin::create([
            'user_id'           => $employee->user_id,
            'gym_id'            => $gym->id,
            'employee_id'       => $employee->id,
            'checked_in_at'     => now(),
            'latitude'          => $request->latitude,
            'longitude'         => $request->longitude,
            'location_verified' => true,
            'checkin_method'    => 'qr_scan',
        ]);

        $visitsThisMonth = $employee->checkinsThisMonth();
        $monthlyLimit    = $employee->subscription->plan->gym_checkins_per_month;

        event(new MemberCheckedIn(
            gymId:            $gym->id,
            cardNo:           $employee->membership_card_no,
            planName:         $employee->subscription->plan->name,
            visitsThisMonth:  $visitsThisMonth,
            monthlyLimit:     $monthlyLimit,
            gymCapacityToday: $gym->dailyCheckinCount(),
            gymDailyLimit:    $gym->daily_capacity_limit,
            status:           'approved',
            reason:           null,
            checkedInAt:      $checkin->checked_in_at->format('H:i'),
        ));

        return response()->json([
            'status'           => 'approved',
            'card_no'          => $employee->membership_card_no,
            'plan'             => $employee->subscription->plan->name,
            'visits_this_month' => $visitsThisMonth,
            'monthly_limit'    => $monthlyLimit,
            'checked_in_at'    => $checkin->checked_in_at->toISOString(),
        ]);
    }

    private function deny(Request $request, ?Gym $gym, int $status, string $reason, ?Employee $employee = null): JsonResponse
    {
        if ($gym) {
            $plan = $employee?->subscription?->plan;
            event(new MemberCheckedIn(
                gymId:            $gym->id,
                cardNo:           $employee?->membership_card_no ?? $request->member_card,
                planName:         $plan?->name ?? '—',
                visitsThisMonth:  $employee ? $employee->checkinsThisMonth() : 0,
                monthlyLimit:     $plan?->gym_checkins_per_month ?? 0,
                gymCapacityToday: $gym->dailyCheckinCount(),
                gymDailyLimit:    $gym->daily_capacity_limit,
                status:           'denied',
                reason:           $reason,
                checkedInAt:      now()->format('H:i'),
            ));
        }

        return response()->json(['status' => 'denied', 'reason' => $reason], $status);
    }
}
