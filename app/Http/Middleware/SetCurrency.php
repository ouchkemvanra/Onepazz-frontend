<?php

namespace App\Http\Middleware;

use App\Models\PlatformConfig;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SetCurrency
{
    public function handle(Request $request, Closure $next): mixed
    {
        $currency = session('currency', 'usd');

        if (!in_array($currency, ['usd', 'khr'])) {
            $currency = 'usd';
        }

        $khrRate = (float) PlatformConfig::get('khr_rate', 4100);

        View::share('currency', $currency);
        View::share('khrRate', $khrRate);

        return $next($request);
    }
}
