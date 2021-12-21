<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Exceptions\UnexpectedErrorLogger;
use App\Models\Shopper;
use ErrorException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller {

    /**
     * Calculates the value of a user's cart
     * @param Collection
     * @return Response
     */
    private function cartPrice($cart) {
        return round($cart->reduce(
            fn ($prev, $item): float => $prev + $item->price * $item->details->amount,
            0.0,
        ), 2);
    }

    /**
     * Gets the list of products, their corresponding amount and the cart value of a user
     *
     * @return Response
     */
    public function get() {
        try {
            $user = Auth::user();
            $shopper = Shopper::find($user->id);
            $cart = $shopper->cart;
            $cartPrice = $this->cartPrice($cart);

            $cartObj = json_decode($cart->toJson());

            array_map(function ($prod) {
                $prod->attributes = json_decode($prod->attributes);
                $prod->amount = $prod->details->amount;
                unset($prod->details);
            }, $cartObj);

            return response()->json([
                'items' => $cartObj,
                'total' => $cartPrice,
            ]);
        } catch (Exception $err) {
            UnexpectedErrorLogger::log($err);
            return ApiError::unexpected();
        }
    }
}
