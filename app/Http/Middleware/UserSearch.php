<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserSearch extends Search {
    private function sanitizeName(Request $request) {
        $this->sanitizeNumeric($request, 'price-min', 0);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        return $next($request);
    }
}
