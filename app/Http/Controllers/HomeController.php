<?php

namespace App\Http\Controllers;

use App\Models\Gym;
use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $plans        = Plan::where('is_active', true)->orderBy('display_order')->get();
        $featuredGyms = Gym::active()->orderByDesc('average_rating')->limit(3)->get();

        return view('home', compact('plans', 'featuredGyms'));
    }
}
