<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NotificationSearch extends Search {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $this->searchSanitize($request);
        return $next($request);
    }
}
