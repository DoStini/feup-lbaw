<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ApiError;
use Illuminate\Support\Facades\Config;

class EnsureAdmin
{
    /**
     * Checks if authenticated user is an admin
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::user()->is_admin) {
            return $next($request);
        } else {
            $extra = [
                "errors" => [
                    "Authentication" => "User is not an admin"
                ]
            ];
            $obj = Config::get("constants.authentication.auth");

            return ApiError::generateErrorMessage($obj, $extra);
        }
    }
}
