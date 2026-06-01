<?php

namespace App\Http\Controllers;

use App\Mail\GymApplicationReceivedMail;
use App\Models\GymApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GymApplicationController extends Controller
{
    public function create()
    {
        return view('gym-apply.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'studio_name'    => 'required|string|max:150',
            'studio_name_kh' => 'nullable|string|max:150',
            'contact_name'   => 'required|string|max:100',
            'contact_email'  => 'required|email|max:150',
            'contact_phone'  => 'nullable|string|max:20',
            'address'        => 'required|string|max:255',
            'district'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'activity_types' => 'nullable|array',
            'description'    => 'nullable|string|max:2000',
            'website'        => 'nullable|url|max:255',
            'agree_terms'    => 'accepted',
        ]);

        unset($data['agree_terms']);
        $data['status'] = 'pending';
        $data['source']  = 'application';

        $application = GymApplication::create($data);

        Mail::to($application->contact_email)
            ->send(new GymApplicationReceivedMail($application));

        // Notify all platform admins
        User::where('role', 'platform_admin')->get()->each(function ($admin) use ($application) {
            Mail::to($admin->email)->send(new \App\Mail\GymAdminNotificationMail($application));
        });

        return redirect()->route('gym-apply.thank-you');
    }

    public function thankYou()
    {
        return view('gym-apply.thank-you');
    }

    public function terms()
    {
        return view('gym-apply.terms');
    }

    public function acceptInvite(string $token)
    {
        $application = GymApplication::where('invite_token', $token)->firstOrFail();

        if ($application->isInviteExpired()) {
            return view('gym-apply.invite-expired');
        }

        return view('gym-apply.accept', compact('application', 'token'));
    }

    public function submitAccepted(Request $request, string $token)
    {
        $application = GymApplication::where('invite_token', $token)->firstOrFail();

        if ($application->isInviteExpired()) {
            return view('gym-apply.invite-expired');
        }

        $data = $request->validate([
            'studio_name'    => 'required|string|max:150',
            'studio_name_kh' => 'nullable|string|max:150',
            'contact_name'   => 'required|string|max:100',
            'contact_phone'  => 'nullable|string|max:20',
            'address'        => 'required|string|max:255',
            'district'       => 'required|string|max:100',
            'city'           => 'required|string|max:100',
            'activity_types' => 'nullable|array',
            'description'    => 'nullable|string|max:2000',
            'website'        => 'nullable|url|max:255',
        ]);

        $application->update(array_merge($data, ['status' => 'under_review']));

        // Notify admins that invite was accepted
        User::where('role', 'platform_admin')->get()->each(function ($admin) use ($application) {
            Mail::to($admin->email)->send(new \App\Mail\GymAdminNotificationMail($application, 'accepted'));
        });

        return redirect()->route('gym-apply.thank-you');
    }
}
