<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClassBookingController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\GymPortalController;
use App\Http\Controllers\GymApplicationController;
use App\Http\Controllers\Dashboard\BillingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EmployerController as AdminEmployerController;
use App\Http\Controllers\EmployerRegistrationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
Route::post('/currency/{currency}', [CurrencyController::class, 'switch'])->name('currency.switch');
Route::get('/gyms',       [GymController::class, 'index'])->name('gyms.index');
Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');

// Employer Self-Service Registration (public)
Route::get('/register/employer',                           [EmployerRegistrationController::class, 'create'])->name('employer-register.create');
Route::post('/register/employer',                          [EmployerRegistrationController::class, 'store'])->name('employer-register.store');
Route::get('/register/employer/thank-you',                 [EmployerRegistrationController::class, 'thankYou'])->name('employer-register.thank-you');
Route::get('/register/employer/accept/{token}',            [EmployerRegistrationController::class, 'acceptInvite'])->name('employer-register.accept');
Route::post('/register/employer/accept/{token}',           [EmployerRegistrationController::class, 'submitAccepted'])->name('employer-register.submit');
Route::get('/register/employer/invite-expired',            fn() => view('employer-register.invite-expired'))->name('employer-register.invite-expired');

// Partner Self-Service Application (public)
Route::get('/join',               [GymApplicationController::class, 'create'])->name('gym-apply.create');
Route::post('/join',              [GymApplicationController::class, 'store'])->name('gym-apply.store');
Route::get('/join/thank-you',     [GymApplicationController::class, 'thankYou'])->name('gym-apply.thank-you');
Route::get('/join/terms',         [GymApplicationController::class, 'terms'])->name('gym-apply.terms');
Route::get('/gym-apply/accept/{token}',  [GymApplicationController::class, 'acceptInvite'])->name('gym-apply.accept');
Route::post('/gym-apply/accept/{token}', [GymApplicationController::class, 'submitAccepted'])->name('gym-apply.submit');

// User Profile (protected by auth)
Route::middleware('auth')->get('/profile', [ProfileController::class, 'index'])->name('profile.index');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // Reports (employer_admin only)
    Route::middleware('role:employer_admin')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/csv', [ReportController::class, 'downloadCsv'])->name('csv');
        Route::get('/pdf', [ReportController::class, 'downloadPdf'])->name('pdf');
    });

    // Billing (employer_admin only)
    Route::middleware('role:employer_admin')->prefix('billing')->name('billing.')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::get('/pay/{invoice}', [BillingController::class, 'showPayForm'])->name('pay');
        Route::post('/pay/{invoice}', [BillingController::class, 'submitPayment'])->name('pay.store');
    });

    // Employee Management (employer_admin only)
    Route::middleware('role:employer_admin')->prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::post('/{employee}/suspend', [EmployeeController::class, 'suspend'])->name('suspend');
        Route::post('/{employee}/restore', [EmployeeController::class, 'restore'])->name('restore');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Class Bookings (any authenticated user)
Route::middleware('auth')->group(function () {
    Route::post('/gyms/{gym}/classes/{class}/book',   [ClassBookingController::class, 'book'])->name('classes.book');
    Route::delete('/gyms/{gym}/classes/{class}/book', [ClassBookingController::class, 'cancel'])->name('classes.cancel');
    Route::post('/gyms/{gym}/checkin',                [GymController::class, 'checkin'])->name('gyms.checkin');
});

// Gym Partner Portal — read-only views accessible by gym_admin AND all active staff
Route::middleware(['auth', 'gym.staff'])->prefix('gym-portal')->name('gym-portal.')->group(function () {
    Route::get('/',              [GymPortalController::class, 'index'])->name('index');
    Route::get('/earnings',      [GymPortalController::class, 'earnings'])->name('earnings');
    Route::get('/bookings',      [GymPortalController::class, 'bookings'])->name('bookings');
    Route::get('/reviews',       [GymPortalController::class, 'reviews'])->name('reviews');
    Route::get('/checkin-screen',[GymPortalController::class, 'checkinScreen'])->name('checkin-screen');
    Route::get('/qr-code',       [GymPortalController::class, 'qrCode'])->name('qr-code');
});

// Gym Partner Portal — management actions (gym_admin only)
Route::middleware(['auth', 'role:gym_admin'])->prefix('gym-portal')->name('gym-portal.')->group(function () {
    Route::get('/profile',                   [GymPortalController::class, 'profile'])->name('profile');
    Route::patch('/profile',                 [GymPortalController::class, 'updateProfile'])->name('profile.update');
    Route::get('/classes',                   [GymPortalController::class, 'classes'])->name('classes');
    Route::post('/classes',                  [GymPortalController::class, 'storeClass'])->name('classes.store');
    Route::patch('/classes/{class}',         [GymPortalController::class, 'updateClass'])->name('classes.update');
    Route::post('/classes/{class}/toggle',   [GymPortalController::class, 'toggleClass'])->name('classes.toggle');
    Route::post('/qr-code/regenerate',       [GymPortalController::class, 'regenerateQr'])->name('qr-code.regenerate');
    Route::get('/staff',                     [GymPortalController::class, 'staff'])->name('staff.index');
    Route::get('/staff/invite',              [GymPortalController::class, 'inviteStaff'])->name('staff.invite');
    Route::post('/staff/invite',             [GymPortalController::class, 'storeInvite'])->name('staff.store');
    Route::patch('/staff/{staff}/role',      [GymPortalController::class, 'updateRole'])->name('staff.role');
    Route::delete('/staff/{staff}',          [GymPortalController::class, 'removeStaff'])->name('staff.remove');
});

// Admin Routes (platform_admin only)
Route::middleware(['auth', 'platform_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Payment Management
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminController::class, 'showPayment'])->name('payments.show');
    Route::post('/payments/{payment}/confirm', [AdminController::class, 'confirmPayment'])->name('payments.confirm');
    Route::post('/payments/{payment}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');

    // Gym Application Management
    Route::get('/gym-applications', [AdminController::class, 'gymApplications'])->name('gym-applications.index');
    Route::get('/gym-applications/{application}', [AdminController::class, 'showGymApplication'])->name('gym-applications.show');
    Route::post('/gym-applications/{application}/approve', [AdminController::class, 'approveGymApplication'])->name('gym-applications.approve');
    Route::post('/gym-applications/{application}/reject', [AdminController::class, 'rejectGymApplication'])->name('gym-applications.reject');

    // Settings
    Route::get('/settings',                  [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings',                 [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/settings/revenue-config',   [AdminController::class, 'updateRevenueConfig'])->name('settings.revenue-config');
    Route::post('/settings/checkin-radius',   [AdminController::class, 'updateCheckinRadius'])->name('settings.checkin-radius');

    // Gym Management
    Route::get('/gyms',                        [AdminController::class, 'gyms'])->name('gyms.index');
    Route::get('/gyms/create',                 [AdminController::class, 'createGym'])->name('gyms.create');
    Route::post('/gyms',                       [AdminController::class, 'storeGym'])->name('gyms.store');
    Route::get('/gyms/invite',                 [AdminController::class, 'invite'])->name('gyms.invite');
    Route::post('/gyms/invite',                [AdminController::class, 'sendInvite'])->name('gyms.invite.send');
    Route::get('/gyms/invitations',            [AdminController::class, 'invitations'])->name('gyms.invitations');
    Route::post('/gyms/invitations/{application}/resend', [AdminController::class, 'resendInvite'])->name('gyms.invitations.resend');
    Route::delete('/gyms/invitations/{application}',      [AdminController::class, 'cancelInvite'])->name('gyms.invitations.cancel');
    Route::get('/gyms/{gym}/edit',             [AdminController::class, 'editGym'])->name('gyms.edit');
    Route::patch('/gyms/{gym}',                [AdminController::class, 'updateGym'])->name('gyms.update');
    Route::post('/gyms/{gym}/suspend',         [AdminController::class, 'suspendGym'])->name('gyms.suspend');
    Route::post('/gyms/{gym}/activate',        [AdminController::class, 'activateGym'])->name('gyms.activate');
    Route::post('/gyms/{gym}/regenerate-qr',   [AdminController::class, 'regenerateGymQr'])->name('gyms.regenerate-qr');

    // Payouts
    Route::get('/payouts',                   [AdminController::class, 'payouts'])->name('payouts.index');
    Route::post('/payouts/confirm',          [AdminController::class, 'confirmPayouts'])->name('payouts.confirm');
    Route::get('/payouts/export-csv',        [AdminController::class, 'exportPayoutsCsv'])->name('payouts.csv');

    // Employer Management
    Route::get('/employers',                              [AdminEmployerController::class, 'index'])->name('employers.index');
    Route::get('/employers/create',                       [AdminEmployerController::class, 'create'])->name('employers.create');
    Route::post('/employers',                             [AdminEmployerController::class, 'store'])->name('employers.store');
    Route::get('/employers/invite',                       [AdminEmployerController::class, 'invite'])->name('employers.invite');
    Route::post('/employers/invite',                      [AdminEmployerController::class, 'sendInvite'])->name('employers.invite.send');
    Route::post('/employers/invitations/{invitation}/resend', [AdminEmployerController::class, 'resendInvite'])->name('employers.invitations.resend');
    Route::delete('/employers/invitations/{invitation}',  [AdminEmployerController::class, 'cancelInvite'])->name('employers.invitations.cancel');
    Route::get('/employers/{employer}/edit',              [AdminEmployerController::class, 'edit'])->name('employers.edit');
    Route::put('/employers/{employer}',                   [AdminEmployerController::class, 'update'])->name('employers.update');
    Route::patch('/employers/{employer}/suspend',         [AdminEmployerController::class, 'suspend'])->name('employers.suspend');
    Route::patch('/employers/{employer}/activate',        [AdminEmployerController::class, 'activate'])->name('employers.activate');
    Route::post('/employers/{employer}/approve',          [AdminEmployerController::class, 'approvePending'])->name('employers.approve');
    Route::post('/employers/{employer}/reject',           [AdminEmployerController::class, 'rejectPending'])->name('employers.reject');
});