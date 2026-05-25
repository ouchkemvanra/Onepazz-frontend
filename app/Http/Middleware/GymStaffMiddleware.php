<?php

namespace App\Http\Middleware;

use App\Models\GymStaff;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GymStaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $gym  = null;

        if ($user->isGymAdmin()) {
            $gym = $user->adminForGym;
        } else {
            $staffRecord = GymStaff::where('user_id', $user->id)
                ->where('is_active', true)
                ->with('gym')
                ->first();

            $gym = $staffRecord?->gym;
        }

        if (!$gym) {
            abort(403, 'No gym associated with this account.');
        }

        $request->merge(['current_gym' => $gym]);

        return $next($request);
    }
}
