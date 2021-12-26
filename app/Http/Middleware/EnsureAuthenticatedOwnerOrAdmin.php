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
                $response = [];
                $response["errors"] = [
                    "Authentication" => "User is not owner of account or is not an admin"
                ];

                return response()->json($response, 403);
            }
        } else {
            $response = [];
            $response["errors"] = [
                "Authentication" => "User is not authenticated"
            ];

            return response()->json($response, 401);
        }

        return $next($request);
    }
}
