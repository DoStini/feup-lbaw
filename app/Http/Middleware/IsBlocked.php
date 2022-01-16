<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ApiError;
use App\Models\Shopper;
use Illuminate\Support\Facades\Config;

class IsBlocked
{
    /**
     * Checks if athe user is not a blocked shopper
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            $shopper = Shopper ::join('users', 'users.id', '=', 'authenticated_shopper.id')
            ->where('is_blocked', true)
            ->where('users.id', '=', Auth::user()->id)->first();

            if($shopper && $shopper->is_blocked) {
                return redirect(route('blocked'));
            }
        }
        
        return $next($request);
    }
}
