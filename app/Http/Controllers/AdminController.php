<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;


class AdminController extends Controller {

    /**
     * Shows the homepage
     *
     * @return Response
     */
    public function getDashboard() {
        $user = Auth::user();
        if(!$user->is_admin) redirect(RouteServiceProvider::HOME);
        return view('pages.adminDashboard', ['admin' => $user, 'page' => 'generalDashboard']);
    }

}
