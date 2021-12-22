<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Exceptions\UnexpectedErrorLogger;
use App\Models\Product;
use App\Models\Shopper;
use ErrorException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller {

    /**
     * Returns a validator to the add to cart function
     *
     * @return Validator
     */
    private function validatorAdd($request) {
        return Validator::make($request->all(), [
            'product_id' => 'required|integer|min:5',
        ]);
    }

    /**
     * Calculates the value of a user's cart
     * @param Collection
     * @return float
     */
    private function cartPrice($cart) {
        return round($cart->reduce(
            fn ($prev, $item): float => $prev + $item->price * $item->details->amount,
            0.0,
        ), 2);
    }

    private function productInCart($shopper, $product) {
        return $shopper->cart->contains(($product));
    }

    public function add(Request $request) {

        if (($v = $this->validatorAdd($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $amount = $request->input("amount", 1);
        $productId = $request->product_id;
        $shopper = Shopper::find($userId);

        if (!($product = $this->getProduct($productId))) {
            return ApiError::productDoesNotExist();
        }

        if ($this->productInCart($shopper, $product)) {
            return ApiError::productAlreadyInCart();
        }

        $shopper->cart()->attach($productId, ['amount' => $amount]);

        return response()->json();
    }

    /**
     * Gets the list of products, their corresponding amount and the cart value of a user
     *
     * @return \Illuminate\Http\JsonResponse
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

    /**
     * @param $productId
     * @return mixed
     */
    public function getProduct($productId)
    {
        return Product::find($productId);
    }
}
