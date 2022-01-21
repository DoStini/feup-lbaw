<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProductSearch extends Search {
    private function sanitizeMinPrice(Request $request) {
        $this->sanitizeNumeric($request, 'price-min', 0);
    }

    private function sanitizeMaxPrice(Request $request) {
        $this->sanitizeNumeric($request, 'price-max', null);
    }

    private function sanitizeMinRate(Request $request) {
        $this->sanitizeNumeric($request, 'rate-min', 0);
    }

    private function sanitizeMaxRate(Request $request) {
        $this->sanitizeNumeric($request, 'rate-max', null);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $this->sanitizeMaxPrice($request);
        $this->sanitizeMinPrice($request);
        $this->sanitizeMaxRate($request);
        $this->sanitizeMinRate($request);
        $this->searchSanitize($request);
        return $next($request);
    }
}
