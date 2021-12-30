<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class StaticPagesController extends Controller {
    protected $redirectTo = '/';

    /**
     * Shows the homepage
     *
     * @return Response
     */
    public function home() {
        return view('pages.home');
    }

}
