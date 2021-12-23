<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class JoinController extends Controller {
    protected $redirectTo = '/';

    /**
     * Shows the join page
     *
     * @return Response
     */
    public function show() {
        return view('auth.join');
    }
}
