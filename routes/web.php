<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\Dashboard\BillingController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\EmployeeController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
Route::post('/currency/{currency}', [CurrencyController::class, 'switch'])->name('currency.switch');
Route::get('/gyms',       [GymController::class, 'index'])->name('gyms.index');
Route::get('/gyms/{gym}', [GymController::class, 'show'])->name('gyms.show');

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
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});