<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

class IsNotAdmin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $user = Auth::user();
        if ($user->is_admin) {
            throw new AuthenticationException(
                'Admin does not have a cart.',
                [],
                $this->redirectTo($request)
            );
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are an admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            return route('admin');
        }
    }
}
