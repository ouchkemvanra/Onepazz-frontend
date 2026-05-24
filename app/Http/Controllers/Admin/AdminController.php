<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PaymentConfirmedMail;
use App\Mail\PaymentRejectedMail;
use App\Models\Payment;
use App\Models\GymApplication;
use App\Models\PlatformConfig;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        $khrRate = PlatformConfig::get('khr_rate', 4100);
        return view('admin.settings', compact('khrRate'));
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
}
