<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\EmployerController as AdminEmployerController;
use App\Mail\EmployerRegistrationReceivedMail;
use App\Mail\GymAdminNotificationMail;
use App\Models\Employer;
use App\Models\EmployerInvitation;
use App\Models\EmployerSubscription;
use App\Models\Plan;
use App\Models\PlatformConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployerRegistrationController extends Controller
{
    private function plans()
    {
        return Plan::where('is_active', true)->orderBy('display_order')->get();
    }

    private function bankDetails(): array
    {
        return [
            'bank'    => PlatformConfig::get('bank_name', 'ACLEDA Bank'),
            'account' => PlatformConfig::get('bank_account', '1234-5678-9012-3456'),
            'holder'  => PlatformConfig::get('bank_holder', 'OnePazz Co., Ltd'),
        ];
    }

    public function create()
    {
        return view('employer-register.create', [
            'plans'       => $this->plans(),
            'bankDetails' => $this->bankDetails(),
            'invitation'  => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            // Step 1
            'company_name'       => 'required|string|max:150',
            'company_name_kh'    => 'nullable|string|max:150',
            'registration_number'=> 'nullable|string|max:50',
            'industry'           => 'nullable|string|max:80',
            'address_line1'      => 'required|string|max:255',
            'city'               => 'required|string|max:100',
            'province'           => 'required|string|max:100',
            'company_size'       => 'nullable|string|max:20',
            // Step 2
            'contact_name'       => 'required|string|max:100',
            'contact_email'      => 'required|email|unique:employers,contact_email|unique:users,email',
            'contact_phone'      => 'nullable|string|max:20',
            'password'           => 'required|min:8|confirmed',
            // Step 3
            'plan_id'            => 'required|exists:plans,id',
            'employee_count'     => 'required|integer|min:1',
            'billing_cycle'      => 'required|in:monthly,quarterly,annual',
        ]);

        $employer = null;
        $invoice  = null;

        DB::transaction(function () use ($request, &$employer, &$invoice) {
            $refCode = 'EMP-' . now()->year . '-' . str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $adminUser = User::create([
                'full_name' => $request->contact_name,
                'email'     => $request->contact_email,
                'password'  => Hash::make($request->password),
                'role'      => 'employer_admin',
                'is_active' => true,
            ]);

            $employer = Employer::create([
                'company_name'       => $request->company_name,
                'company_name_kh'    => $request->company_name_kh,
                'registration_number'=> $request->registration_number,
                'industry'           => $request->industry,
                'company_size'       => $request->company_size,
                'address_line1'      => $request->address_line1,
                'city'               => $request->city,
                'province'           => $request->province,
                'contact_name'       => $request->contact_name,
                'contact_email'      => $request->contact_email,
                'contact_phone'      => $request->contact_phone,
                'admin_user_id'      => $adminUser->id,
                'status'             => 'pending',
                'source'             => $request->input('invitation_token') ? 'invitation' : 'self_registered',
                'reference_code'     => $refCode,
            ]);

            $plan  = Plan::find($request->plan_id);
            $start = now();

            $sub = EmployerSubscription::create([
                'employer_id'    => $employer->id,
                'plan_id'        => $plan->id,
                'employee_count' => $request->employee_count,
                'billing_cycle'  => $request->billing_cycle,
                'start_date'     => $start,
                'status'         => 'active',
            ]);

            $invoice = AdminEmployerController::generateInvoice($employer, $sub, $plan, $start);
            $invoice->update(['notes' => 'Ref: ' . $refCode]);

            // Mark invitation accepted
            if ($request->invitation_token) {
                EmployerInvitation::where('invite_token', $request->invitation_token)
                    ->update(['status' => 'accepted', 'accepted_at' => now()]);
            }
        });

        $bankDetails = $this->bankDetails();

        Mail::to($employer->contact_email)
            ->send(new EmployerRegistrationReceivedMail($employer, $invoice, $bankDetails));

        // Notify all admins
        User::where('role', 'platform_admin')->each(function ($admin) use ($employer) {
            Mail::to($admin->email)->send(new \App\Mail\GymAdminNotificationMail(
                new \App\Models\GymApplication(['studio_name' => $employer->company_name, 'contact_name' => $employer->contact_name, 'contact_email' => $employer->contact_email, 'city' => $employer->city, 'created_at' => now()])
            ));
        });

        session(['reg_ref_code' => $employer->reference_code, 'reg_total_usd' => $invoice->total_usd, 'reg_total_khr' => $invoice->total_khr]);

        return redirect()->route('employer-register.thank-you');
    }

    public function thankYou()
    {
        $bankDetails  = $this->bankDetails();
        $refCode      = session('reg_ref_code');
        $totalUsd     = session('reg_total_usd');
        $totalKhr     = session('reg_total_khr');

        return view('employer-register.thank-you', compact('bankDetails', 'refCode', 'totalUsd', 'totalKhr'));
    }

    public function acceptInvite(string $token)
    {
        $invitation = EmployerInvitation::where('invite_token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('employer-register.invite-expired');
        }

        return view('employer-register.accept', [
            'plans'       => $this->plans(),
            'invitation'  => $invitation,
        ]);
    }

    public function submitAccepted(Request $request, string $token)
    {
        $invitation = EmployerInvitation::where('invite_token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('employer-register.invite-expired');
        }

        return $this->store($request->merge(['invitation_token' => $token]));
    }
}
