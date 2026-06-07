<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\GymAdminNotificationMail;
use App\Mail\GymApplicationApprovedMail;
use App\Mail\GymApplicationRejectedMail;
use App\Mail\GymInvitationMail;
use App\Mail\GymWelcomeMail;
use App\Mail\PaymentConfirmedMail;
use App\Mail\PaymentRejectedMail;
use App\Models\PartnerPayout;
use App\Models\Payment;
use App\Models\GymApplication;
use App\Models\PlatformConfig;
use App\Models\Gym;
use App\Models\User;
use App\Services\RevenueShareService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'pending_gym_applications' => GymApplication::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // Payment Management
    public function payments()
    {
        $payments = Payment::with(['employer', 'invoice'])
            ->orderByRaw("FIELD(status, 'pending', 'confirmed', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    public function showPayment(Payment $payment)
    {
        $payment->load(['employer', 'invoice', 'confirmedBy']);
        return view('admin.payments.show', compact('payment'));
    }

    public function confirmPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($payment, $request) {
            $payment->update([
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
                'notes' => $request->notes,
            ]);

            // Update invoice status to paid
            if ($payment->invoice) {
                $payment->invoice->update(['status' => 'paid']);
            }

            // Update employer subscription status to active
            if ($payment->invoice && $payment->invoice->subscription) {
                $payment->invoice->subscription->update(['status' => 'active']);
            }
        });

        $payment->load(['employer.adminUser', 'invoice']);
        Mail::to($payment->employer->adminUser->email)
            ->send(new PaymentConfirmedMail($payment));

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment confirmed successfully');
    }

    public function rejectPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payment->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'confirmed_by' => auth()->id(),
        ]);

        $payment->load(['employer.adminUser', 'invoice']);
        Mail::to($payment->employer->adminUser->email)
            ->send(new PaymentRejectedMail($payment, $request->rejection_reason));

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment rejected');
    }

    // Gym Application Management
    public function gymApplications()
    {
        $applications = GymApplication::with('reviewer')
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.gym-applications.index', compact('applications'));
    }

    public function showGymApplication(GymApplication $application)
    {
        $application->load('reviewer');
        return view('admin.gym-applications.show', compact('application'));
    }

    public function approveGymApplication(Request $request, GymApplication $application)
    {
        $request->validate([
            'tier'                  => 'required|in:bronze,silver,gold',
            'partner_since'         => 'required|date',
            'create_gym_admin'      => 'nullable|boolean',
            'admin_user_email'      => 'required_if:create_gym_admin,1|nullable|email',
            'notes'                 => 'nullable|string|max:500',
        ]);

        $adminUser   = null;
        $tempPassword = null;
        $gym         = null;

        DB::transaction(function () use ($application, $request, &$adminUser, &$tempPassword, &$gym) {
            $slug = Str::slug($application->studio_name);
            $base = $slug;
            $i = 1;
            while (Gym::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i++;
            }

            $gymData = [
                'name'          => $application->studio_name,
                'name_kh'       => $application->studio_name_kh,
                'slug'          => $slug,
                'address_line1' => $application->address,
                'district'      => $application->district,
                'city'          => $application->city,
                'activity_types'=> $application->activity_types,
                'description'   => $application->description,
                'website'       => $application->website,
                'phone'         => $application->contact_phone,
                'email'         => $application->contact_email,
                'status'        => 'active',
                'tier'          => $request->tier,
                'partner_since' => $request->partner_since,
            ];

            if ($request->boolean('create_gym_admin')) {
                $tempPassword = Str::random(10);
                $adminUser = User::create([
                    'full_name' => $application->contact_name,
                    'email'     => $request->admin_user_email,
                    'password'  => bcrypt($tempPassword),
                    'role'      => 'gym_admin',
                    'is_active' => true,
                ]);
                $gymData['admin_user_id'] = $adminUser->id;
            }

            $gym = Gym::create($gymData);
            $gym->generateQrToken();

            $application->update([
                'status'      => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
                'notes'       => $request->notes,
            ]);
        });

        Mail::to($application->contact_email)
            ->send(new GymApplicationApprovedMail(
                $application,
                $adminUser?->email,
                $tempPassword
            ));

        if ($adminUser && $tempPassword) {
            Mail::to($adminUser->email)->send(new GymWelcomeMail($adminUser, $gym, $tempPassword));
        }

        return redirect()->route('admin.gym-applications.index')
            ->with('success', "Application approved. Gym '{$gym->name}' created.");
    }

    public function rejectGymApplication(Request $request, GymApplication $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $application->update([
            'status'           => 'rejected',
            'reviewed_at'      => now(),
            'reviewed_by'      => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        Mail::to($application->contact_email)
            ->send(new GymApplicationRejectedMail($application, $request->rejection_reason));

        return redirect()->route('admin.gym-applications.index')
            ->with('success', 'Gym application rejected and applicant notified.');
    }

    // Settings Management
    public function settings()
    {
        $khrRate              = PlatformConfig::get('khr_rate', 4100);
        $checkinsGold         = PlatformConfig::get('checkins_per_unit_gold', 15);
        $checkinsSilver       = PlatformConfig::get('checkins_per_unit_silver', 20);
        $checkinsBronze       = PlatformConfig::get('checkins_per_unit_bronze', 25);
        $revenueShareDefault  = PlatformConfig::get('revenue_share_pct_default', 30);
        $checkinRadiusDefault = PlatformConfig::get('checkin_radius_default', 50);

        return view('admin.settings', compact(
            'khrRate', 'checkinsGold', 'checkinsSilver', 'checkinsBronze', 'revenueShareDefault', 'checkinRadiusDefault'
        ));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'khr_rate' => 'required|numeric|min:1|max:50000',
        ]);

        PlatformConfig::set('khr_rate', $request->khr_rate);

        return redirect()->route('admin.settings')
            ->with('success', 'Exchange rate updated successfully');
    }

    public function updateCheckinRadius(Request $request)
    {
        $request->validate(['checkin_radius_default' => 'required|integer|min:10|max:5000']);
        PlatformConfig::set('checkin_radius_default', $request->checkin_radius_default);
        return redirect()->route('admin.settings')->with('success', 'Default check-in radius updated.');
    }

    public function updateRevenueConfig(Request $request)
    {
        $request->validate([
            'checkins_per_unit_gold'    => 'required|integer|min:1',
            'checkins_per_unit_silver'  => 'required|integer|min:1',
            'checkins_per_unit_bronze'  => 'required|integer|min:1',
            'revenue_share_pct_default' => 'required|numeric|min:0|max:100',
        ]);

        PlatformConfig::set('checkins_per_unit_gold',    $request->checkins_per_unit_gold);
        PlatformConfig::set('checkins_per_unit_silver',  $request->checkins_per_unit_silver);
        PlatformConfig::set('checkins_per_unit_bronze',  $request->checkins_per_unit_bronze);
        PlatformConfig::set('revenue_share_pct_default', $request->revenue_share_pct_default);

        return redirect()->route('admin.settings')
            ->with('success', 'Revenue share settings updated.');
    }

    // Gym Management
    public function gyms()
    {
        $gyms = Gym::withTrashed()->orderBy('name')->paginate(30);
        return view('admin.gyms.index', compact('gyms'));
    }

    public function editGym(Gym $gym)
    {
        return view('admin.gyms.edit', compact('gym'));
    }

    public function updateGym(Request $request, Gym $gym)
    {
        $request->validate([
            'monthly_fee_usd'        => 'required|numeric|min:0',
            'revenue_share_pct'      => 'required|numeric|min:0|max:100',
            'daily_capacity_limit'   => 'nullable|integer|min:1',
            'tier'                   => 'required|in:bronze,silver,gold',
            'checkin_radius_meters'  => 'required|integer|min:10|max:5000',
        ]);

        $gym->update($request->only([
            'monthly_fee_usd', 'revenue_share_pct', 'daily_capacity_limit', 'tier', 'checkin_radius_meters',
        ]));

        return redirect()->route('admin.gyms.index')
            ->with('success', "Gym settings updated for {$gym->name}.");
    }

    public function regenerateGymQr(Gym $gym)
    {
        $gym->generateQrToken();
        return back()->with('success', 'QR token regenerated for ' . $gym->name . '.');
    }

    public function suspendGym(Gym $gym)
    {
        $gym->update(['status' => 'suspended']);
        return back()->with('success', "{$gym->name} suspended.");
    }

    public function activateGym(Gym $gym)
    {
        $gym->update(['status' => 'active']);
        return back()->with('success', "{$gym->name} activated.");
    }

    // Payouts
    public function payouts(Request $request, RevenueShareService $service)
    {
        $year  = (int) $request->input('year',  now()->year);
        $month = (int) $request->input('month', now()->subMonth()->month);

        $payouts    = $service->calculatePartnerPayouts($year, $month);
        $confirmed  = PartnerPayout::where('year', $year)->where('month', $month)->get()->keyBy('gym_id');
        $totalPayout = $payouts->sum('payout_usd');
        $totalCut    = $payouts->sum('onepazz_cut_usd');

        return view('admin.payouts.index', compact(
            'payouts', 'confirmed', 'year', 'month', 'totalPayout', 'totalCut'
        ));
    }

    public function confirmPayouts(Request $request, RevenueShareService $service)
    {
        $year  = (int) $request->input('year',  now()->year);
        $month = (int) $request->input('month', now()->subMonth()->month);

        $khrRate = (float) PlatformConfig::get('khr_rate', 4100);
        $payouts = $service->calculatePartnerPayouts($year, $month);

        foreach ($payouts as $row) {
            PartnerPayout::updateOrCreate(
                ['gym_id' => $row['gym_id'], 'year' => $year, 'month' => $month],
                [
                    'checkins'       => $row['checkins'],
                    'units'          => $row['units'],
                    'value_per_unit' => $row['value_per_unit'],
                    'payout_usd'     => $row['payout_usd'],
                    'onepazz_cut'   => $row['onepazz_cut_usd'],
                    'khr_rate'       => $khrRate,
                    'payout_khr'     => $row['payout_khr'],
                    'status'         => 'confirmed',
                    'confirmed_at'   => now(),
                    'confirmed_by'   => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.payouts.index', compact('year', 'month'))
            ->with('success', 'Payouts confirmed for ' . \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') . '.');
    }

    public function exportPayoutsCsv(Request $request, RevenueShareService $service): StreamedResponse
    {
        $year  = (int) $request->input('year',  now()->year);
        $month = (int) $request->input('month', now()->subMonth()->month);
        $rows  = $service->calculatePartnerPayouts($year, $month);
        $label = \Carbon\Carbon::createFromDate($year, $month, 1)->format('Y-m');

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Partner', 'Tier', 'Check-ins', 'Units', 'Value/Unit', 'Payout USD', 'OnePazz Cut', 'Payout KHR']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['gym_name'], $r['tier'], $r['checkins'], $r['units'],
                    $r['value_per_unit'], $r['payout_usd'], $r['onepazz_cut_usd'], $r['payout_khr'],
                ]);
            }
            fclose($out);
        }, "payouts-{$label}.csv", ['Content-Type' => 'text/csv']);
    }

    // ── Admin Direct Add Gym ───────────────────────────────────────────────────

    public function createGym()
    {
        return view('admin.gyms.create');
    }

    public function storeGym(Request $request)
    {
        $request->validate([
            'name'                   => 'required|string|max:150',
            'name_kh'                => 'nullable|string|max:150',
            'description'            => 'nullable|string',
            'description_kh'         => 'nullable|string',
            'tier'                   => 'required|in:bronze,silver,gold',
            'address_line1'          => 'nullable|string|max:255',
            'district'               => 'nullable|string|max:100',
            'city'                   => 'nullable|string|max:100',
            'province'               => 'nullable|string|max:100',
            'latitude'               => 'nullable|numeric|between:-90,90',
            'longitude'              => 'nullable|numeric|between:-180,180',
            'phone'                  => 'nullable|string|max:30',
            'email'                  => 'nullable|email|max:150',
            'website'                => 'nullable|url|max:255',
            'activity_types'         => 'nullable|array',
            'amenities'              => 'nullable|array',
            'daily_capacity_limit'   => 'nullable|integer|min:1',
            'checkin_radius_meters'  => 'required|integer|min:10|max:5000',
            'monthly_fee_usd'        => 'required|numeric|min:0',
            'revenue_share_pct'      => 'required|numeric|min:0|max:100',
            'status'                 => 'required|in:pending,active,suspended',
            'partner_since'          => 'nullable|date',
            'create_gym_admin'       => 'nullable|boolean',
            'admin_user_email'       => 'required_if:create_gym_admin,1|nullable|email',
        ]);

        $slug = Str::slug($request->name);
        $base = $slug;
        $i = 1;
        while (Gym::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $adminUser   = null;
        $tempPassword = null;

        DB::transaction(function () use ($request, $slug, &$adminUser, &$tempPassword) {
            $gymData = array_merge($request->only([
                'name', 'name_kh', 'description', 'description_kh', 'tier',
                'address_line1', 'district', 'city', 'province',
                'latitude', 'longitude', 'phone', 'email', 'website',
                'activity_types', 'amenities', 'daily_capacity_limit',
                'checkin_radius_meters', 'monthly_fee_usd', 'revenue_share_pct',
                'status', 'partner_since',
            ]), ['slug' => $slug]);

            $hours = [];
            foreach (['mon','tue','wed','thu','fri','sat','sun'] as $day) {
                $hours[$day] = [
                    'open'   => $request->input("hours.{$day}.open", '08:00'),
                    'close'  => $request->input("hours.{$day}.close", '21:00'),
                    'closed' => $request->boolean("hours.{$day}.closed"),
                ];
            }
            $gymData['operating_hours'] = $hours;

            if ($request->boolean('create_gym_admin')) {
                $tempPassword = Str::random(10);
                $adminUser = User::create([
                    'full_name' => $request->input('admin_full_name', $request->name . ' Admin'),
                    'email'     => $request->admin_user_email,
                    'password'  => bcrypt($tempPassword),
                    'role'      => 'gym_admin',
                    'is_active' => true,
                ]);
                $gymData['admin_user_id'] = $adminUser->id;
            }

            $gym = Gym::create($gymData);
            $gym->generateQrToken();

            if ($adminUser && $tempPassword) {
                Mail::to($adminUser->email)->send(new GymWelcomeMail($adminUser, $gym, $tempPassword));
            }
        });

        return redirect()->route('admin.gyms.index')
            ->with('success', "Gym '{$request->name}' created successfully.");
    }

    // ── Invitation System ──────────────────────────────────────────────────────

    public function invite()
    {
        return view('admin.gyms.invite');
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'contact_name'    => 'required|string|max:100',
            'contact_email'   => 'required|email|max:150',
            'studio_name'     => 'nullable|string|max:150',
            'personal_message'=> 'nullable|string|max:1000',
            'tier'            => 'required|in:bronze,silver,gold',
        ]);

        $application = GymApplication::create([
            'contact_name'   => $request->contact_name,
            'contact_email'  => $request->contact_email,
            'studio_name'    => $request->studio_name ?? $request->contact_name . "'s Gym",
            'status'         => 'pending',
            'source'         => 'invitation',
            'invite_token'   => (string) Str::uuid(),
            'invite_sent_at' => now(),
            'invite_expires_at' => now()->addDays(7),
            'invited_by'     => auth()->id(),
        ]);

        Mail::to($application->contact_email)
            ->send(new GymInvitationMail($application, $request->personal_message));

        return redirect()->route('admin.gyms.invitations')
            ->with('success', "Invitation sent to {$request->contact_email}.");
    }

    public function invitations()
    {
        $invitations = GymApplication::where('source', 'invitation')
            ->with('invitedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        return view('admin.gyms.invitations', compact('invitations'));
    }

    public function resendInvite(GymApplication $application)
    {
        $application->update([
            'invite_sent_at'    => now(),
            'invite_expires_at' => now()->addDays(7),
        ]);

        Mail::to($application->contact_email)
            ->send(new GymInvitationMail($application));

        return back()->with('success', "Invitation resent to {$application->contact_email}.");
    }

    public function cancelInvite(GymApplication $application)
    {
        $application->delete();
        return back()->with('success', 'Invitation cancelled.');
    }
}
