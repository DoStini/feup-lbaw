<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Search {

    protected $defaultPageSize = 10;

    public function sanitizeNumeric(Request $request, $field, $default) {
        if (!is_numeric($request[$field])) {
            $request[$field] = $default;
        }
    }

    /**
     *  Sets a default value for page
     * 
     *  @param  \Illuminate\Http\Request  $request
     */
    public function sanitizePage(Request $request) {
        $this->sanitizeNumeric($request, 'page', 1);
        $request->page -= 1;
    }

    /**
     *  Sets a default value for page
     * 
     *  @param  \Illuminate\Http\Request  $request
     */
    public function sanitizePageSize(Request $request) {
        $this->sanitizeNumeric($request, 'page-size', $this->defaultPageSize);
    }

    public function searchSanitize(Request $request) {
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
