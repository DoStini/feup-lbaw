<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Shopper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use stdClass;

class AdminController extends Controller {

    /**
     * Shows the homepage
     *
     * @return Response
     */
    public function getDashboard() {
        $user = Auth::user();
        if(!$user->is_admin) redirect(RouteServiceProvider::HOME);

        $info = new stdClass();

        $info->userNum = Shopper::leftJoin('users', 'users.id', '=', 'authenticated_shopper.id')
                                  ->where('is_deleted', false)->where('is_blocked', false)->count();

        $info->orderNum = Order::where('status', '<>', 'shipped')->count();
        $info->productNum = Product::where('stock', '<>', 0)->count();
        $info->proposedProductNum =0;

        return view('pages.adminDashboard', ['admin' => $user, 'info' => $info, 'page' => 'generalDashboard']);
    }

}
