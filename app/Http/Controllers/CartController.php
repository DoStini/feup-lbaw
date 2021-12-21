<?php

namespace App\Http\Controllers;

use App\Models\Shopper;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller {

    private function cartPrice($cart) {
    }

    /**
     * Shows the product for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function get() {
        $user = Auth::user();
        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;


        $cart_obj = json_decode($cart->toJson());
        array_map(function ($prod) {
            $prod->attributes = json_decode($prod->attributes);
            $prod->amount = $prod->details->amount;
            unset($prod->details);
        }, $cart_obj);
        return response()->json([
            $cart_obj,
        ]);
    }
}
