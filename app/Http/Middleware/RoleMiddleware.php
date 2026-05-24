<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized.');
        }

        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized. ' . ucfirst(str_replace('_', ' ', $role)) . ' access required.');
        }

        return $next($request);
    }
}
