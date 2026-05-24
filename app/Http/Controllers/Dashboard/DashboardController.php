<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $employer = $user->adminForEmployer;

        if (!$employer) {
            abort(403, 'No employer account found.');
        }

        $sub = $employer->activeSubscription()->with('plan')->first();

        // KPI metrics
        $activeEmployees = $employer->employees()->where('status', 'active')->count();

        $checkinsThisMonth = Checkin::whereHas('employee', fn($q) =>
            $q->where('employer_id', $employer->id))
            ->whereMonth('checked_in_at', now()->month)
            ->whereYear('checked_in_at', now()->year)
            ->count();

        $monthlyCostUsd = $employer->monthlyTotalUsd();

        $utilisationRate = $activeEmployees > 0
            ? round(
                $employer->employees()
                    ->whereHas('checkins', fn($q) =>
                        $q->whereMonth('checked_in_at', now()->month))
                    ->count() / $activeEmployees * 100, 1)
            : 0;

        // Weekly check-ins (last 7 days)
        $weeklyCheckins = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date  = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('D d');
            $count = Checkin::whereHas('employee', fn($q) =>
                $q->where('employer_id', $employer->id))
                ->whereDate('checked_in_at', $date)
                ->count();
            $weeklyCheckins->push(['label' => $label, 'count' => $count, 'today' => $i === 0]);
        }

        // Top gyms
        $topGyms = Checkin::whereHas('employee', fn($q) =>
            $q->where('employer_id', $employer->id))
            ->whereMonth('checked_in_at', now()->month)
            ->select('gym_id', DB::raw('COUNT(*) as visits'))
            ->with('gym:id,name')
            ->groupBy('gym_id')
            ->orderByDesc('visits')
            ->limit(5)
            ->get();

        // Recent employees
        $employees = $employer->employees()
            ->with(['user', 'subscription.plan'])
            ->withCount(['checkins' => fn($q) =>
                $q->whereMonth('checked_in_at', now()->month)])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboard.index', compact(
            'employer', 'sub', 'activeEmployees', 'checkinsThisMonth',
            'monthlyCostUsd', 'utilisationRate', 'weeklyCheckins',
            'topGyms', 'employees'
        ));
    }
}