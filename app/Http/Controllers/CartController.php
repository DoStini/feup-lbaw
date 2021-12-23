<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Shopper;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {
    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function show() {
        if (!Auth::check()) return redirect('/join');
        //$this->authorize('showCart');
        $user = Auth::user();

        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;

        return view('pages.cart', ['cart' => $cart]);
    }
}
