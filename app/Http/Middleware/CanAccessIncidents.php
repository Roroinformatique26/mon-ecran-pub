<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanAccessIncidents
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            abort(403);
        }

        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'incident') {
            abort(403);
        }

        return $next($request);
    }
}
