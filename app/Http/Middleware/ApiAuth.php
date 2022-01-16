<?php

namespace App\Http\Middleware;

use App\Models\Shopper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuth
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
            return $next($request);
        } else {
            $response = [];
            $response["errors"] = [
                "Authentication" => "User is not authenticated"
            ];

            return response()->json($response, 401);
        }

    }
}
