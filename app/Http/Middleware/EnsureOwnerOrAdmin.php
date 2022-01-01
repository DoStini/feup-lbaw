<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureOwnerOrAdmin
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
        if(!(strval(Auth::id()) === $request->route('id') || Auth::user()->is_admin)) {
            $response = [];
            $response["errors"] = [
                "Authentication" => "User is not owner of account or is not an admin"
            ];

            return response()->json($response, 403);
        }

        return $next($request);
    }
}
