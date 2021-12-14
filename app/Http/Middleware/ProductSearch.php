<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ProductSearch extends Search {
    public function sanitizeMinPrice(Request $request) {
        $this->sanitizeNumeric($request, 'price-min', 0);
    }

    public function sanitizeMaxPrice(Request $request) {
        $this->sanitizeNumeric($request, 'price-max', null);
        if (
            $request->input('price-max') &&
            $request->input('price-max') < $request->input('price-min')
        ) {
            $swap = $request->input('price-max');
            $request['price-max'] = $request->input('price-min');
            $request['price-min'] = $swap;
        }
    }

    public function sanitizeMinStars(Request $request) {
        $this->sanitizeNumeric($request, 'stars-min', 0);
    }

    public function sanitizeMaxStars(Request $request) {
        $this->sanitizeNumeric($request, 'stars-max', 5);
        if (
            $request->input('stars-max') &&
            $request->input('stars-max') < $request->input('stars-min')
        ) {
            $swap = $request->input('stars-max');
            $request['stars-max'] = $request->input('stars-min');
            $request['stars-min'] = $swap;
        }
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
        $this->searchSanitize($request);
        return $next($request);
    }
}
