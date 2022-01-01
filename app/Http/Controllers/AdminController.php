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

        $this->authorize('isAdmin', User::class);

        $info = new stdClass();

        $info->userNum = Shopper::leftJoin('users', 'users.id', '=', 'authenticated_shopper.id')
                                  ->where('is_deleted', false)->where('is_blocked', false)->count();

        $info->orderNum = Order::where('status', '<>', 'shipped')->count();

        $info->productNum = Product::where('stock', '<', 2)->count();

        $info->proposedProductNum = 0;

        return view('pages.adminDashboard', ['admin' => Auth::user(), 'info' => $info, 'page' => 'generalDashboard']);
    }

    public function getOrderDashboard() {

        $this->authorize('isAdmin', User::class);

        $info = new stdClass();

        $info->updatableOrders = Order::where('status', '<>', 'shipped')
                                 ->leftJoin('users', 'order.shopper_id', '=', 'users.id')
                                 ->orderBy('status')
                                 ->orderBy('timestamp', 'asc')
                                 ->get(['order.id','name','shopper_id','timestamp','total','status']); //need to add created_at and updated_at in sql

        $info->finishedOrders = Order::where('status', '=', 'shipped')
                                ->leftJoin('users', 'order.shopper_id', '=', 'users.id')
                                ->orderBy('status')
                                ->orderBy('timestamp', 'asc')
                                ->get(['order.id','name','shopper_id','timestamp','total','status']); //need to add created_at and updated_at in sql

        return view('pages.adminDashboard', ['admin' => Auth::user(), 'info' => $info, 'page' => 'orderDashboard']);
    }

    public function getUserDashboard() {

        $this->authorize('isAdmin', User::class);

        return view('pages.adminDashboard', ['admin' => Auth::user(), 'page' => 'userDashboard']);
    }
}
