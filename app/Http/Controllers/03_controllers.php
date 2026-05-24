<?php
// ============================================================
// KhmerFit — Controllers
// Each section = separate file under app/Http/Controllers/
// ============================================================


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Auth/AuthController.php
// ─────────────────────────────────────────────────────────────
namespace App\Http\Controllers\Auth;

class AuthController extends Controller
{
    // POST /auth/register
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'full_name'          => $request->full_name,
            'full_name_kh'       => $request->full_name_kh,
            'email'              => $request->email,
            'password'           => Hash::make($request->password),
            'phone'              => $request->phone,
            'preferred_lang'     => $request->preferred_lang ?? 'en',
            'preferred_currency' => $request->preferred_currency ?? 'usd',
            'role'               => 'member',
        ]);

        // Send email verification
        $user->sendEmailVerificationNotification();

        // Log in immediately
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('profile.index');
    }

    // POST /auth/login
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => __('auth.failed')])->withInput();
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Redirect based on role
        return match($user->role) {
            'platform_admin' => redirect()->route('admin.dashboard'),
            'employer_admin' => redirect()->route('dashboard.index'),
            'gym_admin'      => redirect()->route('gym-portal.index'),
            default          => redirect()->route('profile.index'),
        };
    }

    // POST /auth/logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/GymController.php
// ─────────────────────────────────────────────────────────────
namespace App\Http\Controllers;

class GymController extends Controller
{
    // GET /gyms — Gym directory
    public function index(Request $request)
    {
        $query = Gym::active()
            ->with(['reviews'])
            ->when($request->filled('search'), fn($q) =>
                $q->where(function($q) use ($request) {
                    $q->where('name', 'like', "%{$request->search}%")
                      ->orWhere('name_kh', 'like', "%{$request->search}%")
                      ->orWhere('city', 'like', "%{$request->search}%")
                      ->orWhereJsonContains('activity_types', $request->search);
                })
            )
            ->when($request->filled('tier'),     fn($q) => $q->whereIn('tier', (array)$request->tier))
            ->when($request->filled('city'),     fn($q) => $q->byCity($request->city))
            ->when($request->filled('activity'), fn($q) => $q->whereJsonContains('activity_types', $request->activity))
            ->when($request->filled('amenity'),  fn($q) => $q->whereJsonContains('amenities', $request->amenity));

        // Sort
        match($request->sort) {
            'name'     => $query->orderBy('name'),
            'rating'   => $query->orderByDesc('average_rating'),
            'distance' => $this->sortByDistance($query, $request),
            default    => $query->orderByDesc('average_rating'),
        };

        $gyms = $query->paginate(12)->withQueryString();

        return view('gyms.index', compact('gyms'));
    }

    // GET /gyms/{slug} — Gym profile
    public function show(Gym $gym)
    {
        abort_if($gym->status !== 'active', 404);

        $gym->load(['reviews.user', 'classes' => fn($q) => $q->where('is_active', true)]);

        // Today's classes
        $todayClasses = $gym->classes->filter(fn($c) => $c->isToday())
                            ->sortBy('start_time');

        // Is this gym saved by the current user?
        $isSaved = auth()->check()
            ? auth()->user()->savedGyms()->where('gym_id', $gym->id)->exists()
            : false;

        // Visits this month by current employee
        $visitsThisMonth = 0;
        $monthlyLimit    = 0;
        if (auth()->check()) {
            $employee = auth()->user()->employees()->first();
            if ($employee) {
                $visitsThisMonth = $employee->checkinsThisMonth();
                $monthlyLimit    = $employee->subscription?->plan?->gym_checkins_per_month ?? 10;
            }
        }

        return view('gyms.show', compact('gym', 'todayClasses', 'isSaved', 'visitsThisMonth', 'monthlyLimit'));
    }

    // POST /gyms/{gym}/checkin
    public function checkin(Request $request, Gym $gym)
    {
        $user = auth()->user();
        $employee = $user->employees()->whereHas('subscription', fn($q) => $q->where('status', 'active'))->first();

        if (!$employee) {
            return back()->with('error', 'No active membership found.');
        }

        if ($employee->hasReachedMonthlyLimit()) {
            return back()->with('error', 'Monthly visit limit reached.');
        }

        Checkin::create([
            'user_id'        => $user->id,
            'gym_id'         => $gym->id,
            'employee_id'    => $employee->id,
            'class_id'       => $request->class_id,
            'checked_in_at'  => now(),
        ]);

        return back()->with('success', "Checked in at {$gym->name}!");
    }

    // POST /gyms/{gym}/save  (toggle save/unsave)
    public function toggleSave(Gym $gym)
    {
        $user = auth()->user();
        $exists = $user->savedGyms()->where('gym_id', $gym->id)->exists();

        if ($exists) {
            $user->savedGyms()->detach($gym->id);
            return response()->json(['saved' => false]);
        }

        $user->savedGyms()->attach($gym->id, ['saved_at' => now()]);
        return response()->json(['saved' => true]);
    }

    private function sortByDistance($query, $request)
    {
        // Requires user lat/lng from browser geolocation
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $query->selectRaw("*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
                [$lat, $lng, $lat]
            )->orderBy('distance');
        }
        return $query;
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Dashboard/DashboardController.php
// ─────────────────────────────────────────────────────────────
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:employer_admin']);
    }

    // GET /dashboard
    public function index()
    {
        $employer = auth()->user()->adminForEmployer;
        abort_unless($employer, 403);

        $sub = $employer->activeSubscription()->with('plan')->first();

        // KPI metrics
        $activeEmployees   = $employer->employees()->where('status', 'active')->count();
        $checkinsThisMonth = Checkin::whereHas('employee', fn($q) => $q->where('employer_id', $employer->id))
            ->whereMonth('checked_in_at', now()->month)->count();
        $monthlyCostUsd    = $employer->monthlyTotalUsd();
        $utilisationRate   = $activeEmployees > 0
            ? round(($employer->employees()->whereHas('checkins', fn($q) =>
                $q->whereMonth('checked_in_at', now()->month))->count() / $activeEmployees) * 100, 1)
            : 0;

        // Weekly check-ins (last 7 days)
        $weeklyCheckins = Checkin::whereHas('employee', fn($q) => $q->where('employer_id', $employer->id))
            ->where('checked_in_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(checked_in_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        // Top gyms by visits this month
        $topGyms = Checkin::whereHas('employee', fn($q) => $q->where('employer_id', $employer->id))
            ->whereMonth('checked_in_at', now()->month)
            ->selectRaw('gym_id, COUNT(*) as visits')
            ->with('gym:id,name')
            ->groupBy('gym_id')
            ->orderByDesc('visits')
            ->limit(5)
            ->get();

        // Recent employees
        $recentEmployees = $employer->employees()
            ->with(['user', 'subscription.plan'])
            ->withCount(['checkins' => fn($q) => $q->whereMonth('checked_in_at', now()->month)])
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get();

        $khrRate = (float) PlatformConfig::get('khr_rate', 4100);

        return view('dashboard.index', compact(
            'employer', 'sub', 'activeEmployees', 'checkinsThisMonth',
            'monthlyCostUsd', 'utilisationRate', 'weeklyCheckins',
            'topGyms', 'recentEmployees', 'khrRate'
        ));
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Dashboard/EmployeeController.php
// ─────────────────────────────────────────────────────────────
class EmployeeController extends Controller
{
    // GET /dashboard/employees
    public function index()
    {
        $employer   = auth()->user()->adminForEmployer;
        $employees  = $employer->employees()
            ->with(['user', 'subscription.plan'])
            ->withCount(['checkins' => fn($q) => $q->whereMonth('checked_in_at', now()->month)])
            ->paginate(25);

        return view('dashboard.employees.index', compact('employees'));
    }

    // POST /dashboard/employees/invite
    public function invite(InviteEmployeeRequest $request)
    {
        $employer   = auth()->user()->adminForEmployer;
        $sub        = $employer->activeSubscription;

        // Create or find the user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['full_name' => $request->full_name ?? explode('@', $request->email)[0], 'role' => 'member']
        );

        // If new user, generate temporary password and send invite email
        if ($user->wasRecentlyCreated) {
            $tempPassword = Str::random(12);
            $user->update(['password' => Hash::make($tempPassword)]);
            Mail::to($user->email)->send(new EmployeeInviteMail($user, $employer, $tempPassword));
        }

        // Create employee record
        $cardNo = $this->generateCardNo($sub);
        Employee::updateOrCreate(
            ['user_id' => $user->id, 'employer_id' => $employer->id],
            [
                'subscription_id'    => $sub->id,
                'department'         => $request->department,
                'job_title'          => $request->job_title,
                'membership_card_no' => $cardNo,
                'status'             => 'active',
                'joined_date'        => today(),
            ]
        );

        // Increment subscription employee count
        $sub->increment('employee_count');

        return back()->with('success', "{$user->full_name} has been added to your team.");
    }

    // PATCH /dashboard/employees/{employee}/suspend
    public function suspend(Employee $employee, Request $request)
    {
        $this->authorize('manage', $employee);
        $employee->update([
            'status'           => 'suspended',
            'suspended_at'     => now(),
            'suspended_reason' => $request->reason,
        ]);
        return back()->with('success', 'Employee access suspended.');
    }

    // PATCH /dashboard/employees/{employee}/restore
    public function restore(Employee $employee)
    {
        $this->authorize('manage', $employee);
        $employee->update(['status' => 'active', 'suspended_at' => null, 'suspended_reason' => null]);
        return back()->with('success', 'Employee access restored.');
    }

    private function generateCardNo(EmployerSubscription $sub): string
    {
        $planCode = strtoupper(substr($sub->plan->name, 0, 3));  // PRO, STA, ENT
        $count    = Employee::count() + 1;
        return sprintf('KF-%d-%s-%05d', now()->year, $planCode, $count);
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Dashboard/BillingController.php
// ─────────────────────────────────────────────────────────────
class BillingController extends Controller
{
    // GET /dashboard/billing
    public function index()
    {
        $employer = auth()->user()->adminForEmployer;
        $invoices = $employer->invoices()->latest()->paginate(12);
        $bankDetails = [
            'bank'    => PlatformConfig::get('bank_name', 'ACLEDA Bank'),
            'account' => PlatformConfig::get('bank_account', '1234-5678-9012-3456'),
            'holder'  => PlatformConfig::get('bank_holder', 'KhmerFit Co., Ltd'),
        ];
        return view('dashboard.billing.index', compact('employer', 'invoices', 'bankDetails'));
    }

    // GET /dashboard/billing/pay/{invoice}
    public function showPayForm(Invoice $invoice)
    {
        $this->authorize('pay', $invoice);
        $bankDetails = [
            'bank'    => PlatformConfig::get('bank_name'),
            'account' => PlatformConfig::get('bank_account'),
            'holder'  => PlatformConfig::get('bank_holder'),
        ];
        return view('dashboard.billing.pay', compact('invoice', 'bankDetails'));
    }

    // POST /dashboard/billing/pay/{invoice}
    public function submitPayment(PaymentRequest $request, Invoice $invoice)
    {
        $this->authorize('pay', $invoice);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')
                ->store("receipts/{$invoice->employer_id}/{$invoice->id}", 'private');
        }

        Payment::create([
            'invoice_id'         => $invoice->id,
            'employer_id'        => $invoice->employer_id,
            'amount_usd'         => $invoice->total_usd,
            'amount_khr'         => $invoice->total_khr,
            'transfer_reference' => $request->transfer_reference,
            'transfer_date'      => $request->transfer_date,  // DD/MM/YYYY parsed by cast
            'bank_name'          => $request->bank_name,
            'receipt_path'       => $receiptPath,
            'status'             => 'pending',
        ]);

        $invoice->update(['status' => 'pending_verification']);

        // Notify platform admins
        $admins = User::where('role', 'platform_admin')->get();
        Notification::send($admins, new PaymentSubmittedNotification($invoice));

        return redirect()->route('dashboard.billing.index')
            ->with('success', 'Payment submitted. We will confirm within 1–2 business days.');
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Dashboard/ReportController.php
// ─────────────────────────────────────────────────────────────
class ReportController extends Controller
{
    // GET /dashboard/reports/csv?month=2026-05
    public function downloadCsv(Request $request)
    {
        $employer = auth()->user()->adminForEmployer;
        $month    = $request->month ?? now()->format('Y-m');
        [$year, $mon] = explode('-', $month);

        $checkins = Checkin::whereHas('employee', fn($q) => $q->where('employer_id', $employer->id))
            ->whereYear('checked_in_at', $year)
            ->whereMonth('checked_in_at', $mon)
            ->with(['user:id,full_name,email', 'gym:id,name,city', 'employee:id,department,membership_card_no'])
            ->orderByDesc('checked_in_at')
            ->get();

        $filename = "khmerfit-report-{$month}.csv";
        $headers  = [
            'Content-Type'        => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($checkins) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM so Excel renders Khmer correctly
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Membership Card','Employee Name','Email','Department','Gym','City','Date','Time','Duration (min)']);

            foreach ($checkins as $c) {
                fputcsv($handle, [
                    $c->employee?->membership_card_no,
                    $c->user?->full_name,
                    $c->user?->email,
                    $c->employee?->department,
                    $c->gym?->name,
                    $c->gym?->city,
                    $c->checked_in_at->format('d/m/Y'),
                    $c->checked_in_at->format('H:i'),
                    $c->duration_minutes,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    // GET /dashboard/reports/pdf?month=2026-05
    public function downloadPdf(Request $request)
    {
        $employer = auth()->user()->adminForEmployer;
        $month    = $request->month ?? now()->format('Y-m');
        [$year, $mon] = explode('-', $month);

        $checkins = Checkin::whereHas('employee', fn($q) => $q->where('employer_id', $employer->id))
            ->whereYear('checked_in_at', $year)->whereMonth('checked_in_at', $mon)
            ->with(['user', 'gym', 'employee'])->orderByDesc('checked_in_at')->get();

        $khrRate   = (float) PlatformConfig::get('khr_rate', 4100);
        $sub       = $employer->activeSubscription()->with('plan')->first();

        $pdf = Pdf::loadView('reports.usage-pdf', compact('employer', 'checkins', 'month', 'khrRate', 'sub'));
        $pdf->setPaper('A4');

        return $pdf->download("khmerfit-report-{$month}.pdf");
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Admin/AdminPaymentController.php
// ─────────────────────────────────────────────────────────────
class AdminPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:platform_admin']);
    }

    // GET /admin/payments
    public function index()
    {
        $pending   = Payment::with(['invoice', 'employer'])->where('status', 'pending')->latest()->get();
        $confirmed = Payment::with(['invoice', 'employer'])->where('status', 'confirmed')->latest()->paginate(20);
        return view('admin.payments.index', compact('pending', 'confirmed'));
    }

    // PATCH /admin/payments/{payment}/confirm
    public function confirm(Payment $payment)
    {
        DB::transaction(function() use ($payment) {
            // 1. Mark payment confirmed
            $payment->update([
                'status'       => 'confirmed',
                'confirmed_at' => now(),
                'confirmed_by' => auth()->id(),
            ]);

            // 2. Mark invoice paid
            $payment->invoice->update(['status' => 'paid']);

            // 3. Ensure subscription stays active
            $payment->invoice->subscription->update(['status' => 'active']);

            // 4. Notify employer
            $adminUser = $payment->employer->adminUser;
            if ($adminUser) {
                Notification::send($adminUser, new PaymentConfirmedNotification($payment->invoice));
            }
        });

        return back()->with('success', "Payment for {$payment->employer->company_name} confirmed.");
    }

    // PATCH /admin/payments/{payment}/reject
    public function reject(Request $request, Payment $payment)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $payment->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        $payment->invoice->update(['status' => 'unpaid']);

        $adminUser = $payment->employer->adminUser;
        if ($adminUser) {
            Notification::send($adminUser, new PaymentRejectedNotification($payment, $request->reason));
        }

        return back()->with('success', 'Payment rejected and employer notified.');
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/Admin/AdminConfigController.php
// ─────────────────────────────────────────────────────────────
class AdminConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:platform_admin']);
    }

    // GET /admin/config
    public function index()
    {
        $config = PlatformConfig::all()->pluck('value', 'key');
        return view('admin.config', compact('config'));
    }

    // PATCH /admin/config/exchange-rate
    public function updateExchangeRate(Request $request)
    {
        $request->validate([
            'khr_rate' => 'required|numeric|min:100|max:100000',
        ]);

        PlatformConfig::where('key', 'khr_rate')->update([
            'value'      => (string) $request->khr_rate,
            'updated_by' => auth()->id(),
        ]);

        // Bust any cached config
        Cache::forget('platform_config');

        return back()->with('success', "Exchange rate updated: 1 USD = {$request->khr_rate} ៛");
    }

    // PATCH /admin/config/bank-details
    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_name'    => 'required|string',
            'bank_account' => 'required|string',
            'bank_holder'  => 'required|string',
        ]);

        foreach (['bank_name', 'bank_account', 'bank_holder'] as $key) {
            PlatformConfig::where('key', $key)->update(['value' => $request->$key, 'updated_by' => auth()->id()]);
        }

        return back()->with('success', 'Bank details updated.');
    }
}


// ─────────────────────────────────────────────────────────────
// app/Helpers/PlatformConfig.php — cached config helper
// ─────────────────────────────────────────────────────────────
class PlatformConfig
{
    public static function get(string $key, $default = null): ?string
    {
        return Cache::remember("platform_config:{$key}", 3600, function() use ($key) {
            return \DB::table('platform_config')->where('key', $key)->value('value');
        }) ?? $default;
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/LanguageController.php
// ─────────────────────────────────────────────────────────────
class LanguageController extends Controller
{
    // POST /language/{locale}
    public function switch(string $locale)
    {
        abort_unless(in_array($locale, ['en', 'km']), 404);

        session(['locale' => $locale]);

        // If authenticated, persist preference
        if ($user = auth()->user()) {
            $user->update(['preferred_lang' => $locale === 'km' ? 'kh' : 'en']);
        }

        return back();
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Controllers/CurrencyController.php
// ─────────────────────────────────────────────────────────────
class CurrencyController extends Controller
{
    // POST /currency/{currency}
    public function switch(string $currency)
    {
        abort_unless(in_array($currency, ['usd', 'khr']), 404);

        session(['currency' => $currency]);

        if ($user = auth()->user()) {
            $user->update(['preferred_currency' => $currency]);
        }

        return back();
    }
}
