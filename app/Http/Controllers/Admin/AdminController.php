<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            'admin_user_email' => 'required|email',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($application, $request) {
            // Create gym admin user
            $adminUser = User::create([
                'full_name' => $application->contact_name,
                'email' => $request->admin_user_email,
                'password' => bcrypt('temporary_password_' . rand(1000, 9999)),
                'phone' => $application->contact_phone,
                'role' => 'gym_admin',
                'is_active' => true,
            ]);

            // Create gym
            $gym = Gym::create([
                'name' => $application->studio_name,
                'name_kh' => $application->studio_name_kh,
                'admin_user_id' => $adminUser->id,
                'address' => $application->address,
                'district' => $application->district,
                'city' => $application->city,
                'activity_types' => $application->activity_types,
                'description' => $application->description,
                'website' => $application->website,
                'contact_phone' => $application->contact_phone,
                'contact_email' => $application->contact_email,
                'status' => 'active',
            ]);

            // Update application
            $application->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.gym-applications.index')
            ->with('success', 'Gym application approved and gym created');
    }

    public function rejectGymApplication(Request $request, GymApplication $application)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $application->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('admin.gym-applications.index')
            ->with('success', 'Gym application rejected');
    }

    // Settings Management
    public function settings()
    {
        $khrRate              = PlatformConfig::get('khr_rate', 4100);
        $checkinsGold         = PlatformConfig::get('checkins_per_unit_gold', 15);
        $checkinsSilver       = PlatformConfig::get('checkins_per_unit_silver', 20);
        $checkinsBronze       = PlatformConfig::get('checkins_per_unit_bronze', 25);
        $revenueShareDefault  = PlatformConfig::get('revenue_share_pct_default', 30);

        return view('admin.settings', compact(
            'khrRate', 'checkinsGold', 'checkinsSilver', 'checkinsBronze', 'revenueShareDefault'
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
            'monthly_fee_usd'       => 'required|numeric|min:0',
            'revenue_share_pct'     => 'required|numeric|min:0|max:100',
            'daily_capacity_limit'  => 'nullable|integer|min:1',
            'tier'                  => 'required|in:bronze,silver,gold',
        ]);

        $gym->update($request->only([
            'monthly_fee_usd', 'revenue_share_pct', 'daily_capacity_limit', 'tier',
        ]));

        return redirect()->route('admin.gyms.index')
            ->with('success', "Gym settings updated for {$gym->name}.");
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
        $totalCut    = $payouts->sum('khmerfit_cut_usd');

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
                    'khmerfit_cut'   => $row['khmerfit_cut_usd'],
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
            fputcsv($out, ['Partner', 'Tier', 'Check-ins', 'Units', 'Value/Unit', 'Payout USD', 'KhmerFit Cut', 'Payout KHR']);
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r['gym_name'], $r['tier'], $r['checkins'], $r['units'],
                    $r['value_per_unit'], $r['payout_usd'], $r['khmerfit_cut_usd'], $r['payout_khr'],
                ]);
            }
            fclose($out);
        }, "payouts-{$label}.csv", ['Content-Type' => 'text/csv']);
    }
}
