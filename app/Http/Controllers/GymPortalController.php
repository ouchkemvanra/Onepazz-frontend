<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\ClassBooking;
use App\Models\GymClass;
use App\Models\PlatformConfig;
use App\Services\RevenueShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GymPortalController extends Controller
{
    private function gym()
    {
        return auth()->user()->adminForGym;
    }

    public function index()
    {
        $gym = $this->gym();
        abort_if(!$gym, 403, 'No gym linked to this account.');

        $now   = now();
        $year  = $now->year;
        $month = $now->month;

        $checkinsThisMonth = $gym->checkins()
            ->whereYear('checked_in_at', $year)
            ->whereMonth('checked_in_at', $month)
            ->count();

        $checkinsPerUnit = $gym->checkinsPerUnit();
        $units           = $checkinsPerUnit > 0 ? floor($checkinsThisMonth / $checkinsPerUnit) : 0;

        $khrRate        = (float) PlatformConfig::get('khr_rate', 4100);
        $defaultSharePct = $gym->effectiveRevenueSharePct();

        // Estimate payout — value_per_unit is unknown until month close; show 0 for live month
        $estimatedPayoutUsd = 0;
        $estimatedPayoutKhr = 0;

        // Bar chart: daily check-ins last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date        = now()->subDays($i);
            $chartData[] = [
                'label' => $date->format('D'),
                'count' => $gym->checkins()->whereDate('checked_in_at', $date)->count(),
            ];
        }

        // Recent check-ins (last 20)
        $recentCheckins = Checkin::where('gym_id', $gym->id)
            ->with(['employee'])
            ->latest('checked_in_at')
            ->take(20)
            ->get();

        $todayCount  = $gym->dailyCheckinCount();
        $dailyLimit  = $gym->daily_capacity_limit;

        return view('gym-portal.index', compact(
            'gym', 'checkinsThisMonth', 'checkinsPerUnit', 'units',
            'estimatedPayoutUsd', 'estimatedPayoutKhr', 'khrRate',
            'defaultSharePct', 'chartData', 'recentCheckins',
            'todayCount', 'dailyLimit'
        ));
    }

    public function earnings()
    {
        $gym  = $this->gym();
        abort_if(!$gym, 403);

        $khrRate = (float) PlatformConfig::get('khr_rate', 4100);
        $months  = [];

        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->startOfMonth()->subMonths($i);
            $year  = (int) $date->format('Y');
            $month = (int) $date->format('n');

            $checkins        = $gym->checkins()
                ->whereYear('checked_in_at', $year)
                ->whereMonth('checked_in_at', $month)
                ->count();
            $checkinsPerUnit = $gym->checkinsPerUnit();
            $units           = $checkinsPerUnit > 0 ? floor($checkins / $checkinsPerUnit) : 0;
            $sharePct        = $gym->effectiveRevenueSharePct();

            // Check for confirmed payout record
            $payout = \App\Models\PartnerPayout::where('gym_id', $gym->id)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            $months[] = [
                'label'       => $date->format('F Y'),
                'year'        => $year,
                'month'       => $month,
                'checkins'    => $checkins,
                'units'       => $units,
                'payout_usd'  => $payout?->payout_usd ?? 0,
                'payout_khr'  => $payout?->payout_khr ?? 0,
                'khmerfit_cut' => $payout?->khmerfit_cut ?? 0,
                'status'      => $payout?->status ?? ($date->isSameMonth(now()) ? 'current' : 'estimated'),
            ];
        }

        return view('gym-portal.earnings', compact('gym', 'months', 'khrRate'));
    }

    public function profile()
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);
        return view('gym-portal.profile', compact('gym'));
    }

    public function updateProfile(Request $request)
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);

        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'name_kh'            => 'nullable|string|max:150',
            'description'        => 'nullable|string',
            'description_kh'     => 'nullable|string',
            'phone'              => 'nullable|string|max:30',
            'email'              => 'nullable|email|max:150',
            'website'            => 'nullable|url|max:255',
            'address_line1'      => 'nullable|string|max:255',
            'district'           => 'nullable|string|max:100',
            'city'               => 'nullable|string|max:100',
            'daily_capacity_limit' => 'nullable|integer|min:1',
            'activity_types'     => 'nullable|array',
            'amenities'          => 'nullable|array',
            'cover_image'        => 'nullable|image|max:4096',
        ]);

        // Operating hours
        $hours = [];
        foreach (['mon','tue','wed','thu','fri','sat','sun'] as $day) {
            $hours[$day] = [
                'open'   => $request->input("hours.{$day}.open", '08:00'),
                'close'  => $request->input("hours.{$day}.close", '21:00'),
                'closed' => $request->boolean("hours.{$day}.closed"),
            ];
        }
        $data['operating_hours'] = $hours;

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('gyms', 'public');
            $data['cover_image_url'] = Storage::url($path);
        }

        unset($data['cover_image']);
        $gym->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function classes()
    {
        $gym     = $this->gym();
        abort_if(!$gym, 403);
        $classes = $gym->classes()->orderBy('start_time')->get();
        return view('gym-portal.classes', compact('gym', 'classes'));
    }

    public function storeClass(Request $request)
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);

        $data = $request->validate([
            'name'             => 'required|string|max:150',
            'name_kh'          => 'nullable|string|max:150',
            'trainer_name'     => 'nullable|string|max:100',
            'class_type'       => 'nullable|string|max:80',
            'day_of_week'      => 'required|array|min:1',
            'day_of_week.*'    => 'integer|between:0,6',
            'start_time'       => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'max_capacity'     => 'required|integer|min:1',
        ]);

        $gym->classes()->create($data);

        return back()->with('success', 'Class created.');
    }

    public function updateClass(Request $request, GymClass $class)
    {
        abort_if($class->gym_id !== $this->gym()?->id, 403);

        $data = $request->validate([
            'name'             => 'required|string|max:150',
            'trainer_name'     => 'nullable|string|max:100',
            'class_type'       => 'nullable|string|max:80',
            'day_of_week'      => 'required|array|min:1',
            'day_of_week.*'    => 'integer|between:0,6',
            'start_time'       => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'max_capacity'     => 'required|integer|min:1',
        ]);

        $class->update($data);

        return back()->with('success', 'Class updated.');
    }

    public function toggleClass(GymClass $class)
    {
        abort_if($class->gym_id !== $this->gym()?->id, 403);
        $class->update(['is_active' => !$class->is_active]);
        return back()->with('success', $class->is_active ? 'Class activated.' : 'Class deactivated.');
    }

    public function bookings(Request $request)
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);

        $date    = $request->input('date', today()->toDateString());
        $classId = $request->input('class_id');

        $query = ClassBooking::whereHas('gymClass', fn($q) => $q->where('gym_id', $gym->id))
            ->with(['gymClass', 'user.employees'])
            ->whereDate('booked_at', $date);

        if ($classId) {
            $query->where('gym_class_id', $classId);
        }

        $bookings = $query->latest('booked_at')->get();
        $classes  = $gym->classes()->orderBy('start_time')->get();

        return view('gym-portal.bookings', compact('gym', 'bookings', 'classes', 'date', 'classId'));
    }

    public function reviews()
    {
        $gym     = $this->gym();
        abort_if(!$gym, 403);

        $reviews = $gym->reviews()->latest()->get();

        $breakdown = [];
        for ($i = 5; $i >= 1; $i--) {
            $count       = $reviews->where('rating', $i)->count();
            $breakdown[] = [
                'stars'   => $i,
                'count'   => $count,
                'percent' => $reviews->count() > 0 ? round(($count / $reviews->count()) * 100) : 0,
            ];
        }

        return view('gym-portal.reviews', compact('gym', 'reviews', 'breakdown'));
    }
}
