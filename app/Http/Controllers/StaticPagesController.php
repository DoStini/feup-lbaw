<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ContactUsAdmin;
use App\Mail\ContactUsUser;
use App\Models\Shopper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
        if (!$user) abort(404);

        $shopper = Shopper::find($user->id);

        if (!$shopper || !$shopper->is_blocked) abort(404);

        Auth::logout();
        return view('pages.staticPages.blocked');
    }

    public function contactUs() {
        return view('pages.staticPages.contactUs');
    }

    public function aboutUs() {
        return view('pages.staticPages.aboutUs');
    }

    private function submitContactValidator($request) {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'message' => 'required|string'
        ]);
    }

    public function submitContact(Request $request) {
        $this->submitContactValidator($request);
        Mail::to(Config::get('mail.from.address'))->send(new ContactUsAdmin($request->name, $request->email, $request->message));
        Mail::to($request->email)->send(new ContactUsUser($request->name, $request->message));

        return view('pages.staticPages.submitted');
    }
}
