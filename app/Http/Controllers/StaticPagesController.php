<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shopper;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller {

    /**
     * Shows the homepage
     *
     * @return Response
     */
    public function home() {
        return view('pages.staticPages.home');
    }

    public function blocked() {
        $user =  Auth::user();
        if(!$user) abort(404);

        $shopper = Shopper::find($user->id);

        if(!$shopper || !$shopper->is_blocked) abort(404);
    
        Auth::logout();
        return view('pages.staticPages.blocked');

    }

    public function contactUs() {
        return view('pages.staticPages.contactUs');
    }

    public function aboutUs() {
        return view('pages.staticPages.aboutUs');
    }

}
