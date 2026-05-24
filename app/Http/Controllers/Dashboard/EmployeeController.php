<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\EmployeeInviteMail;
use App\Models\Employee;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    public function index()
    {
        // Get employer for current admin user
        $employer = Employer::where('admin_user_id', auth()->id())->firstOrFail();

        $employees = Employee::where('employer_id', $employer->id)
            ->with(['user'])
            ->withCount('checkins')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.employees.index', compact('employer', 'employees'));
    }

    public function create()
    {
        $employer = Employer::where('admin_user_id', auth()->id())->firstOrFail();
        return view('dashboard.employees.create', compact('employer'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'full_name' => 'required|string|max:255',
            'department' => 'nullable|string|max:100',
            'job_title' => 'nullable|string|max:100',
            'employee_code' => 'nullable|string|max:50',
        ]);

        $employer = Employer::where('admin_user_id', auth()->id())->firstOrFail();
        $subscription = $employer->activeSubscription;

        if (!$subscription) {
            return back()->with('error', 'No active subscription found. Please activate a subscription first.');
        }

        $tempPassword = 'KF' . Str::random(4) . rand(100, 999);
        $createdUser = null;
        $createdEmployee = null;

        DB::transaction(function () use ($request, $employer, $subscription, $tempPassword, &$createdUser, &$createdEmployee) {
            $createdUser = User::create([
                'full_name' => $request->full_name,
                'email' => $request->email,
                'password' => bcrypt($tempPassword),
                'role' => 'member',
                'is_active' => true,
            ]);

            $createdEmployee = Employee::create([
                'user_id' => $createdUser->id,
                'employer_id' => $employer->id,
                'subscription_id' => $subscription->id,
                'department' => $request->department,
                'job_title' => $request->job_title,
                'employee_code' => $request->employee_code,
                'membership_card_no' => 'KF-' . strtoupper(Str::random(8)),
                'joined_date' => now(),
                'status' => 'active',
            ]);
        });

        Mail::to($createdUser->email)
            ->send(new EmployeeInviteMail($createdUser, $createdEmployee, $employer, $tempPassword));

        return redirect()->route('dashboard.employees.index')
            ->with('success', 'Employee invited successfully. Login credentials have been sent to ' . $createdUser->email . '.');
    }

    public function suspend(Employee $employee)
    {
        $employer = Employer::where('admin_user_id', auth()->id())->firstOrFail();

        // Ensure employee belongs to this employer
        if ($employee->employer_id !== $employer->id) {
            abort(403);
        }

        $employee->update([
            'status' => 'suspended',
            'suspended_at' => now(),
        ]);

        return back()->with('success', 'Employee suspended successfully.');
    }

    public function restore(Employee $employee)
    {
        $employer = Employer::where('admin_user_id', auth()->id())->firstOrFail();

        // Ensure employee belongs to this employer
        if ($employee->employer_id !== $employer->id) {
            abort(403);
        }

        $employee->update([
            'status' => 'active',
            'suspended_at' => null,
            'suspended_reason' => null,
        ]);

        return back()->with('success', 'Employee restored successfully.');
    }
}
