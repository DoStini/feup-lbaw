<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ShopperController extends Controller {

    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function getCart() {
        
        $this->authorize('viewCart');

        $user = Auth::user();

        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;

        return view('pages.cart', ['cart' => $cart]);
    }

    public function getOrders() {
        
        $this->authorize('viewOrders');

        $user = Auth::user();

        $shopper = Shopper::find($user->id);

        return view('pages.profile', ['shopper' => $shopper, 'page' => 'showShopperOrders']);
    }
}
