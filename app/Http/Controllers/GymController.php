<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use Illuminate\Http\Request;

class GymController extends Controller
{
    public function index(Request $request)
    {
        $query = Gym::active();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('city', 'like', "%{$request->search}%")
                  ->orWhere('district', 'like', "%{$request->search}%");
            });
        }

        // Filter by tier
        if ($request->filled('tier')) {
            $query->whereIn('tier', (array) $request->tier);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->whereIn('city', (array) $request->city);
        }

        // Filter by activity
        if ($request->filled('activity')) {
            foreach ((array) $request->activity as $activity) {
                $query->whereJsonContains('activity_types', $activity);
            }
        }

        // Sort
        match($request->sort) {
            'name'   => $query->orderBy('name'),
            'tier'   => $query->orderByRaw("FIELD(tier, 'gold', 'silver', 'bronze')"),
            default  => $query->orderByDesc('average_rating'),
        };

        $gyms = $query->paginate(12)->withQueryString();

        $mapGyms = Gym::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('name', 'slug', 'latitude', 'longitude', 'tier', 'average_rating')
            ->get();

        return view('gyms.index', compact('gyms', 'mapGyms'));
    }

    public function show(Gym $gym)
    {
        abort_if($gym->status !== 'active', 404);

        $gym->load(['reviews.user', 'classes' => fn($q) => $q->where('is_active', true)->orderBy('start_time')]);

        $todayClasses = $gym->classes->filter(fn($c) => $c->isToday());

        $isSaved = auth()->check()
            ? auth()->user()->savedGyms()->where('gym_id', $gym->id)->exists()
            : false;

        return view('gyms.show', compact('gym', 'todayClasses', 'isSaved'));
    }
}