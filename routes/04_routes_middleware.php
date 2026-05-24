<?php
// ============================================================
// KhmerFit — Routes, Middleware, Service Providers, Helpers
// ============================================================


// ─────────────────────────────────────────────────────────────
// routes/web.php
// ─────────────────────────────────────────────────────────────

use Illuminate\Support\Facades\Route;

// ── Public routes ──────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Gym directory (public read)
Route::prefix('gyms')->name('gyms.')->group(function () {
    Route::get('/',        [GymController::class, 'index'])->name('index');
    Route::get('/{gym}',   [GymController::class, 'show'])->name('show');
});

// Gym partner application form (public)
Route::get('/join',         [GymApplicationController::class, 'create'])->name('gym-apply.create');
Route::post('/join',        [GymApplicationController::class, 'store'])->name('gym-apply.store');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/auth/login',              [AuthController::class, 'showLogin'])->name('login');
    Route::post('/auth/login',             [AuthController::class, 'login']);
    Route::get('/auth/register',           [AuthController::class, 'showRegister'])->name('register');
    Route::post('/auth/register',          [AuthController::class, 'register']);
    Route::get('/auth/forgot-password',    [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/auth/forgot-password',   [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/auth/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/auth/reset-password',    [AuthController::class, 'resetPassword'])->name('password.update');
});
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Language & currency toggle
Route::post('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
Route::post('/currency/{currency}', [CurrencyController::class, 'switch'])->name('currency.switch');


// ── Authenticated routes ────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Gym check-in & save (requires login)
    Route::post('/gyms/{gym}/checkin', [GymController::class, 'checkin'])->name('gyms.checkin');
    Route::post('/gyms/{gym}/save',    [GymController::class, 'toggleSave'])->name('gyms.save');

    // User profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',           [ProfileController::class, 'index'])->name('index');
        Route::get('/checkins',   [ProfileController::class, 'checkins'])->name('checkins');
        Route::get('/settings',   [ProfileController::class, 'settings'])->name('settings');
        Route::patch('/settings', [ProfileController::class, 'updateSettings'])->name('settings.update');
    });


    // ── Employer dashboard ──────────────────────────────────
    Route::middleware('role:employer_admin')->prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/',                          [DashboardController::class, 'index'])->name('index');

        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/',                      [EmployeeController::class, 'index'])->name('index');
            Route::get('/invite',                [EmployeeController::class, 'showInvite'])->name('invite');
            Route::post('/invite',               [EmployeeController::class, 'invite'])->name('invite.store');
            Route::patch('/{employee}/suspend',  [EmployeeController::class, 'suspend'])->name('suspend');
            Route::patch('/{employee}/restore',  [EmployeeController::class, 'restore'])->name('restore');
            Route::delete('/{employee}',         [EmployeeController::class, 'remove'])->name('remove');
        });

        Route::get('/plans',                     [PlanController::class, 'index'])->name('plans');

        Route::prefix('billing')->name('billing.')->group(function () {
            Route::get('/',                      [BillingController::class, 'index'])->name('index');
            Route::get('/pay/{invoice}',         [BillingController::class, 'showPayForm'])->name('pay');
            Route::post('/pay/{invoice}',        [BillingController::class, 'submitPayment'])->name('pay.store');
        });

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/',                      [ReportController::class, 'index'])->name('index');
            Route::get('/csv',                   [ReportController::class, 'downloadCsv'])->name('csv');
            Route::get('/pdf',                   [ReportController::class, 'downloadPdf'])->name('pdf');
        });

        Route::get('/settings',                  [EmployerSettingsController::class, 'index'])->name('settings');
        Route::patch('/settings',                [EmployerSettingsController::class, 'update'])->name('settings.update');
    });


    // ── Admin panel ─────────────────────────────────────────
    Route::middleware('role:platform_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                          [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/',                      [AdminPaymentController::class, 'index'])->name('index');
            Route::patch('/{payment}/confirm',   [AdminPaymentController::class, 'confirm'])->name('confirm');
            Route::patch('/{payment}/reject',    [AdminPaymentController::class, 'reject'])->name('reject');
        });

        Route::prefix('employers')->name('employers.')->group(function () {
            Route::get('/',                      [AdminEmployerController::class, 'index'])->name('index');
            Route::get('/{employer}',            [AdminEmployerController::class, 'show'])->name('show');
            Route::patch('/{employer}/suspend',  [AdminEmployerController::class, 'suspend'])->name('suspend');
            Route::patch('/{employer}/activate', [AdminEmployerController::class, 'activate'])->name('activate');
        });

        Route::prefix('gyms')->name('gyms.')->group(function () {
            Route::get('/',                              [AdminGymController::class, 'index'])->name('index');
            Route::get('/applications',                  [AdminGymController::class, 'applications'])->name('applications');
            Route::patch('/applications/{app}/approve',  [AdminGymController::class, 'approve'])->name('approve');
            Route::patch('/applications/{app}/reject',   [AdminGymController::class, 'rejectApp'])->name('reject');
        });

        Route::get('/users',                             [AdminUserController::class, 'index'])->name('users');

        Route::prefix('config')->name('config.')->group(function () {
            Route::get('/',                              [AdminConfigController::class, 'index'])->name('index');
            Route::patch('/exchange-rate',               [AdminConfigController::class, 'updateExchangeRate'])->name('exchange-rate');
            Route::patch('/bank-details',                [AdminConfigController::class, 'updateBankDetails'])->name('bank-details');
        });
    });
});


// ─────────────────────────────────────────────────────────────
// app/Http/Middleware/RoleMiddleware.php
// Register in app/Http/Kernel.php: 'role' => \App\Http\Middleware\RoleMiddleware::class
// ─────────────────────────────────────────────────────────────
namespace App\Http\Middleware;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Middleware/SetLocale.php
// Register in app/Http/Kernel.php web middleware group
// ─────────────────────────────────────────────────────────────
class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Priority: session > authenticated user preference > browser Accept-Language > default
        $locale = session('locale')
            ?? optional(auth()->user())->preferred_lang
            ?? ($request->getPreferredLanguage(['en', 'km']) === 'km' ? 'km' : 'en');

        App::setLocale($locale);

        return $next($request);
    }
}


// ─────────────────────────────────────────────────────────────
// app/Http/Middleware/SetCurrency.php
// ─────────────────────────────────────────────────────────────
class SetCurrency
{
    public function handle(Request $request, Closure $next)
    {
        $currency = session('currency')
            ?? optional(auth()->user())->preferred_currency
            ?? 'usd';

        View::share('activeCurrency', $currency);
        View::share('khrRate', (float) PlatformConfig::get('khr_rate', 4100));

        return $next($request);
    }
}


// ─────────────────────────────────────────────────────────────
// app/Helpers/currency.php — global helper functions
// Add to composer.json autoload.files: ["app/Helpers/currency.php"]
// ─────────────────────────────────────────────────────────────

if (!function_exists('format_currency')) {
    /**
     * Format an amount in USD as USD or KHR based on session preference.
     * Usage in Blade: {{ format_currency(45) }}
     */
    function format_currency(float $amountUsd, ?string $currency = null, ?float $khrRate = null): string
    {
        $currency = $currency ?? session('currency', 'usd');
        $rate     = $khrRate  ?? (float) PlatformConfig::get('khr_rate', 4100);

        if ($currency === 'khr') {
            $khr = number_format(round($amountUsd * $rate));
            return "{$khr} ៛";
        }

        return '$' . number_format($amountUsd, 2);
    }
}

if (!function_exists('format_date_kh')) {
    /**
     * Format a date as DD/MM/YYYY (Cambodian format).
     * Usage: {{ format_date_kh($checkin->checked_in_at) }}
     */
    function format_date_kh($date): string
    {
        return \Carbon\Carbon::parse($date)->format('d/m/Y');
    }
}

if (!function_exists('format_phone_kh')) {
    /**
     * Validate that a phone number is a valid Cambodian number (+855...).
     */
    function is_valid_kh_phone(string $phone): bool
    {
        return (bool) preg_match('/^\+855[1-9]\d{7,8}$/', preg_replace('/\s/', '', $phone));
    }
}


// ─────────────────────────────────────────────────────────────
// app/Providers/AppServiceProvider.php — register observer + config
// ─────────────────────────────────────────────────────────────
class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register model observers
        GymReview::observe(GymReviewObserver::class);

        // Auto-mark overdue invoices (run daily via scheduler)
        // In app/Console/Kernel.php:
        // $schedule->call(function() {
        //     Invoice::where('status', 'unpaid')
        //         ->where('due_date', '<', today())
        //         ->update(['status' => 'overdue']);
        // })->daily();
    }
}


// ─────────────────────────────────────────────────────────────
// config/khmerfit.php — platform-specific config
// ─────────────────────────────────────────────────────────────
return [
    // Default KHR rate (overridden by platform_config DB table)
    'khr_rate'       => env('KHR_RATE', 4100),

    // Supported locales
    'locales'        => ['en' => 'English', 'km' => 'ខ្មែរ'],

    // Supported currencies
    'currencies'     => ['usd' => 'USD ($)', 'khr' => 'KHR (៛)'],

    // Default map center (Phnom Penh)
    'map_lat'        => 11.5564,
    'map_lng'        => 104.9282,
    'map_zoom'       => 13,

    // Cambodian phone regex
    'phone_regex'    => '/^\+855[1-9]\d{7,8}$/',

    // Date format for display
    'date_format'    => 'd/m/Y',
    'datetime_format'=> 'd/m/Y H:i',
];
