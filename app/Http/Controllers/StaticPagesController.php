<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class StaticPagesController extends Controller {

    /**
     * Shows the homepage
     *
     * @return Response
     */
    public function home() {
        return view('pages.home');
    }

}
