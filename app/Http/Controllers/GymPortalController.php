<?php

namespace App\Http\Controllers;

use App\Mail\StaffInviteMail;
use App\Models\Checkin;
use App\Models\ClassBooking;
use App\Models\GymClass;
use App\Models\GymStaff;
use App\Models\PlatformConfig;
use App\Models\User;
use App\Services\RevenueShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GymPortalController extends Controller
{
    private function gym()
    {
        $user = auth()->user();
        if ($user->isGymAdmin()) {
            return $user->adminForGym;
        }
        return $user->gymStaff()->where('is_active', true)->first()?->gym;
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

    // ── QR Code ──────────────────────────────────────────────────────────────

    public function qrCode()
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);

        if (!$gym->qr_code) {
            $gym->generateQrToken();
        }

        $qrSvg = QrCode::format('svg')->size(300)->errorCorrection('H')->generate($gym->qr_code);

        return view('gym-portal.qr-code', compact('gym', 'qrSvg'));
    }

    public function regenerateQr()
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);

        $gym->generateQrToken();

        return back()->with('success', 'QR code regenerated. The old QR code is now invalid.');
    }

    // ── Check-in Screen ───────────────────────────────────────────────────────

    public function checkinScreen(Request $request)
    {
        $gym = $request->input('current_gym'); // injected by GymStaffMiddleware
        abort_if(!$gym, 403);

        $todayCheckins = Checkin::where('gym_id', $gym->id)
            ->whereDate('checked_in_at', today())
            ->with('employee')
            ->latest('checked_in_at')
            ->get();

        $staffRole = auth()->user()->isGymAdmin()
            ? 'Owner'
            : ucfirst(auth()->user()->gymStaffRoleAt($gym->id) ?? 'Staff');

        return view('gym-portal.checkin-screen', compact('gym', 'todayCheckins', 'staffRole'));
    }

    // ── Staff Management ──────────────────────────────────────────────────────

    public function staff()
    {
        $gym   = $this->gym();
        abort_if(!$gym, 403);
        $staff = GymStaff::where('gym_id', $gym->id)->with('user')->get();
        return view('gym-portal.staff.index', compact('gym', 'staff'));
    }

    public function inviteStaff()
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);
        return view('gym-portal.staff.index', ['gym' => $gym, 'staff' => GymStaff::where('gym_id', $gym->id)->with('user')->get(), 'showForm' => true]);
    }

    public function storeInvite(Request $request)
    {
        $gym = $this->gym();
        abort_if(!$gym, 403);

        $request->validate([
            'email' => 'required|email',
            'role'  => 'required|in:cashier,receptionist,trainer,manager',
        ]);

        $tempPassword = null;
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $tempPassword = Str::random(10);
            $user = User::create([
                'full_name' => explode('@', $request->email)[0],
                'email'     => $request->email,
                'password'  => Hash::make($tempPassword),
                'role'      => 'member',
                'is_active' => true,
            ]);
        }

        $staffRecord = GymStaff::updateOrCreate(
            ['user_id' => $user->id, 'gym_id' => $gym->id],
            ['role' => $request->role, 'is_active' => true, 'invited_by' => auth()->id(), 'joined_at' => now()]
        );

        if ($staffRecord->trashed()) {
            $staffRecord->restore();
        }

        Mail::to($user->email)->send(new StaffInviteMail($staffRecord, $tempPassword));

        return back()->with('success', "{$user->email} added as {$request->role}.");
    }

    public function updateRole(Request $request, GymStaff $staff)
    {
        abort_if($staff->gym_id !== $this->gym()?->id, 403);

        $request->validate(['role' => 'required|in:cashier,receptionist,trainer,manager']);
        $staff->update(['role' => $request->role]);

        return back()->with('success', 'Role updated.');
    }

    public function removeStaff(GymStaff $staff)
    {
        abort_if($staff->gym_id !== $this->gym()?->id, 403);
        $staff->delete();
        return back()->with('success', 'Staff member removed.');
    }
}
