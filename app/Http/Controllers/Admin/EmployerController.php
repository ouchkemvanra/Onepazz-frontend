<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\EmployerActivatedMail;
use App\Mail\EmployerInvitationMail;
use App\Mail\EmployerRejectedMail;
use App\Mail\EmployerWelcomeMail;
use App\Models\Employer;
use App\Models\EmployerInvitation;
use App\Models\EmployerSubscription;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\PlatformConfig;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployerController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $tab = $request->input('tab', 'active');

        $active = Employer::with(['adminUser', 'activeSubscription.plan'])
            ->whereIn('status', ['active', 'suspended'])
            ->orderBy('company_name')
            ->paginate(25, ['*'], 'active_page');

        $pending = Employer::with(['activeSubscription.plan'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(25, ['*'], 'pending_page');

        $invitations = EmployerInvitation::with(['suggestedPlan', 'invitedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(25, ['*'], 'inv_page');

        return view('admin.employers.index', compact('active', 'pending', 'invitations', 'tab'));
    }

    // ── Create / Store ─────────────────────────────────────────────────────────

    public function create()
    {
        $plans = Plan::where('is_active', true)->orderBy('display_order')->get();
        return view('admin.employers.create', compact('plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name'      => 'required|string|max:150',
            'company_name_kh'   => 'nullable|string|max:150',
            'registration_number'=> 'nullable|string|max:50',
            'industry'          => 'nullable|string|max:80',
            'company_size'      => 'nullable|string|max:20',
            'address_line1'     => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'province'          => 'nullable|string|max:100',
            'contact_name'      => 'required|string|max:100',
            'contact_email'     => 'required|email|max:150|unique:employers,contact_email',
            'contact_phone'     => 'nullable|string|max:20',
            'status'            => 'required|in:pending,active,suspended',
            'plan_id'           => 'nullable|exists:plans,id',
            'employee_count'    => 'nullable|integer|min:1',
            'billing_cycle'     => 'nullable|in:monthly,quarterly,annual',
            'start_date'        => 'nullable|date',
            'create_account'    => 'nullable|boolean',
            'admin_email'       => 'required_if:create_account,1|nullable|email',
            'admin_name'        => 'nullable|string|max:100',
        ]);

        $employer = null;
        $adminUser = null;
        $tempPassword = null;

        DB::transaction(function () use ($request, &$employer, &$adminUser, &$tempPassword) {
            if ($request->boolean('create_account')) {
                $tempPassword = Str::random(10);
                $adminUser = User::create([
                    'full_name' => $request->input('admin_name', $request->contact_name),
                    'email'     => $request->admin_email,
                    'password'  => bcrypt($tempPassword),
                    'role'      => 'employer_admin',
                    'is_active' => true,
                ]);
            }

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
                'status'             => $request->status,
                'admin_user_id'      => $adminUser?->id,
                'source'             => 'admin',
            ]);

            if ($request->plan_id && $request->employee_count) {
                $plan = Plan::find($request->plan_id);
                $start = $request->start_date ? \Carbon\Carbon::parse($request->start_date) : now();

                $sub = EmployerSubscription::create([
                    'employer_id'    => $employer->id,
                    'plan_id'        => $plan->id,
                    'employee_count' => $request->employee_count,
                    'billing_cycle'  => $request->billing_cycle ?? 'monthly',
                    'start_date'     => $start,
                    'status'         => 'active',
                ]);

                // Generate first invoice
                $this->generateInvoice($employer, $sub, $plan, $start);
            }
        });

        if ($adminUser && $tempPassword) {
            Mail::to($adminUser->email)->send(new EmployerWelcomeMail($adminUser, $employer, $tempPassword));
        }

        return redirect()->route('admin.employers.index')
            ->with('success', "Employer '{$employer->company_name}' created.");
    }

    // ── Edit / Update ──────────────────────────────────────────────────────────

    public function edit(Employer $employer)
    {
        $plans = Plan::where('is_active', true)->orderBy('display_order')->get();
        $employer->load(['activeSubscription.plan', 'adminUser']);
        return view('admin.employers.edit', compact('employer', 'plans'));
    }

    public function update(Request $request, Employer $employer)
    {
        $request->validate([
            'company_name'  => 'required|string|max:150',
            'industry'      => 'nullable|string|max:80',
            'company_size'  => 'nullable|string|max:20',
            'address_line1' => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'province'      => 'nullable|string|max:100',
            'contact_name'  => 'required|string|max:100',
            'contact_email' => 'required|email|unique:employers,contact_email,' . $employer->id,
            'contact_phone' => 'nullable|string|max:20',
            'status'        => 'required|in:pending,active,suspended,cancelled',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $employer->update($request->only([
            'company_name', 'company_name_kh', 'registration_number', 'industry',
            'company_size', 'address_line1', 'city', 'province',
            'contact_name', 'contact_email', 'contact_phone', 'status', 'notes',
        ]));

        return redirect()->route('admin.employers.index')
            ->with('success', "'{$employer->company_name}' updated.");
    }

    // ── Suspend / Activate ─────────────────────────────────────────────────────

    public function suspend(Employer $employer)
    {
        $employer->update(['status' => 'suspended']);
        return back()->with('success', "{$employer->company_name} suspended.");
    }

    public function activate(Employer $employer)
    {
        $employer->update(['status' => 'active']);
        Mail::to($employer->contact_email)->send(new EmployerActivatedMail($employer));
        return back()->with('success', "{$employer->company_name} activated.");
    }

    // ── Approve / Reject pending self-registrations ────────────────────────────

    public function approvePending(Employer $employer)
    {
        $employer->update(['status' => 'active']);

        // Mark invoice paid
        $employer->invoices()->where('status', 'unpaid')->latest()->first()?->update(['status' => 'paid']);

        Mail::to($employer->contact_email)->send(new EmployerActivatedMail($employer));

        return back()->with('success', "{$employer->company_name} approved and activated.");
    }

    public function rejectPending(Request $request, Employer $employer)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);

        $employer->update(['status' => 'cancelled']);

        Mail::to($employer->contact_email)
            ->send(new EmployerRejectedMail($employer, $request->rejection_reason));

        return back()->with('success', "{$employer->company_name} rejected.");
    }

    // ── Invitations ────────────────────────────────────────────────────────────

    public function invite()
    {
        $plans = Plan::where('is_active', true)->orderBy('display_order')->get();
        return view('admin.employers.invite', compact('plans'));
    }

    public function sendInvite(Request $request)
    {
        $request->validate([
            'contact_name'     => 'required|string|max:100',
            'contact_email'    => 'required|email|max:150',
            'company_name'     => 'nullable|string|max:150',
            'suggested_plan_id'=> 'nullable|exists:plans,id',
            'personal_message' => 'nullable|string|max:1000',
        ]);

        $invitation = EmployerInvitation::create([
            'invite_token'      => (string) Str::uuid(),
            'contact_name'      => $request->contact_name,
            'contact_email'     => $request->contact_email,
            'company_name'      => $request->company_name,
            'suggested_plan_id' => $request->suggested_plan_id,
            'personal_message'  => $request->personal_message,
            'invited_by'        => auth()->id(),
            'invite_sent_at'    => now(),
            'invite_expires_at' => now()->addDays(14),
            'status'            => 'pending',
        ]);

        Mail::to($invitation->contact_email)->send(new EmployerInvitationMail($invitation));

        return redirect()->route('admin.employers.index', ['tab' => 'invitations'])
            ->with('success', "Invitation sent to {$request->contact_email}.");
    }

    public function resendInvite(EmployerInvitation $invitation)
    {
        $invitation->update([
            'invite_sent_at'    => now(),
            'invite_expires_at' => now()->addDays(14),
            'status'            => 'pending',
        ]);

        Mail::to($invitation->contact_email)->send(new EmployerInvitationMail($invitation));

        return back()->with('success', "Invitation resent to {$invitation->contact_email}.");
    }

    public function cancelInvite(EmployerInvitation $invitation)
    {
        $invitation->delete();
        return back()->with('success', 'Invitation cancelled.');
    }

    // ── Helper: generate invoice ───────────────────────────────────────────────

    public static function generateInvoice(Employer $employer, EmployerSubscription $sub, Plan $plan, $start): Invoice
    {
        $khrRate   = (float) PlatformConfig::get('khr_rate', 4100);
        $months    = match($sub->billing_cycle) { 'quarterly' => 3, 'annual' => 12, default => 1 };
        $start     = \Carbon\Carbon::parse($start);
        $end       = $start->copy()->addMonths($months)->subDay();
        $subtotal  = $sub->employee_count * $plan->price_usd * $months;
        $number    = 'INV-' . now()->format('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        return Invoice::create([
            'invoice_number'       => $number,
            'employer_id'          => $employer->id,
            'subscription_id'      => $sub->id,
            'billing_period_start' => $start,
            'billing_period_end'   => $end,
            'employee_count'       => $sub->employee_count,
            'plan_price_usd'       => $plan->price_usd,
            'subtotal_usd'         => $subtotal,
            'tax_usd'              => 0,
            'total_usd'            => $subtotal,
            'khr_rate'             => $khrRate,
            'total_khr'            => $subtotal * $khrRate,
            'status'               => 'unpaid',
            'due_date'             => now()->addDays(14),
            'notes'                => $employer->reference_code ? 'Ref: ' . $employer->reference_code : null,
        ]);
    }
}
