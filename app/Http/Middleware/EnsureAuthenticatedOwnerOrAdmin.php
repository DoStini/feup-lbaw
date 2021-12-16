<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAuthenticatedOwnerOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            if(!(strval(Auth::id()) === $request->route('id') || Auth::user()->is_admin)) {
                return response("Forbidden Access", 403)->header('Content-Type', 'text\plain');
            }
        } else {
            return response("Not authenticated", 401)->header('Content-Type', 'text\plain');
        }

        return $next($request);
    }
}
