<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Search {

    protected $defaultPageSize = 12;

    protected function sanitizeNumeric(Request $request, $field, $default) {
        if (!is_numeric($request[$field])) {
            $request[$field] = $default;
        }
    }

    /**
     *  Sets a default value for page
     * 
     *  @param  \Illuminate\Http\Request  $request
     */
    private function sanitizePage(Request $request) {
        $this->sanitizeNumeric($request, 'page', 0);
    }

    /**
     *  Sets a default value for page
     * 
     *  @param  \Illuminate\Http\Request  $request
     */
    private function sanitizePageSize(Request $request) {
        $this->sanitizeNumeric($request, 'page-size', $this->defaultPageSize);
    }

    protected function searchSanitize(Request $request) {
        $this->sanitizePage($request);
        $this->sanitizePageSize($request);
    }

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
