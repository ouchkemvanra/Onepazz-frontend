<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get employee record (if user is a member/employee)
        $employee = Employee::where('user_id', $user->id)
            ->with(['employer', 'subscription.plan'])
            ->first();

        // Get check-in history (last 20)
        $checkins = $user->checkins()
            ->with(['gym', 'gymClass'])
            ->orderBy('checked_in_at', 'desc')
            ->limit(20)
            ->get();

        // Get saved gyms
        $savedGyms = $user->savedGyms()
            ->withPivot('saved_at')
            ->orderBy('saved_at', 'desc')
            ->get();

        return view('profile.index', compact('user', 'employee', 'checkins', 'savedGyms'));
    }
}
