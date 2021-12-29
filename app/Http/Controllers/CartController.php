<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Exceptions\UnexpectedErrorLogger;
use App\Models\Cart;
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
use Ramsey\Uuid\Type\Integer;

class CartController extends Controller {

    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function show() {
        if (!Auth::check()) return redirect('/join');
        $user = Auth::user();
        
        //if($user->is_admin) return redirect('/orders');
        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;

        return view('pages.cart', ['cart' => $cart, 'user' => $user]);
    }

    /**
     * Returns a validator to the functions that only require a product id
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorDelete($request) {
        return Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1|exists:product,id',
        ]);
    }

    /**
     * Returns a validator to update cart function
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorUpdate(Request $request) {
        return Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1|exists:product,id',
            'amount' => 'required|integer|min:1'
        ], [], [
            'product_id' => 'product id'
        ]);
    }

    /**
     * Verifies if a product has enough stock
     * 
     * @param Collection
     * @param Integer
     * @return float
     */
    private function validStock($product, $amount) {
        return $product->stock >= $amount;
    }

    /**
     * Calculates the value of a user's cart
     * 
     * @param Collection
     * @return float
     */
    private function cartPrice($cart) {
        return round($cart->reduce(
            fn ($prev, $item): float => $prev + $item->price * $item->details->amount,
            0.0,
        ), 2);
    }

    /**
     * Verifies if a product is in the user's cart
     * 
     * @param Collection
     * @param Integer
     * @return float
     */
    private function productInCart($shopper, $product) {
        return $shopper->cart->contains(($product));
    }

    /**
     * Updates a product amount in the user's cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request) {
        if (($v = $this->validatorUpdate($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $amount = $request->amount;
        $productId = $request->product_id;
        $product = Product::find($productId);
        $shopper = Shopper::find($userId);

        if (!$this->validStock($product, $amount)) {
            return ApiError::notEnoughStock();
        }


        if ($this->productInCart($shopper, $product)) {
            $shopper->cart()->updateExistingPivot($productId, ['amount' => $amount]);
        } else {
            $shopper->cart()->attach($productId, ['amount' => $amount]);
        }

        return response()->json();
    }

    /**
     * Deletes a product from the user's cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {
        if (($v = $this->validatorDelete($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $productId = $request->product_id;
        $product = Product::find($productId);
        $shopper = Shopper::find($userId);

        if (!$this->productInCart($shopper, $product)) {
            return ApiError::productNotInCart();
        }

        $shopper->cart()->detach($productId);

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

            $cartJson = $cart->map(
                function ($product) {
                    $prodJson = json_decode($product->toJson());
                    $prodJson->photos = $product->photos->map(fn ($photo) => $photo->url);
                    $prodJson->attributes = json_decode($prodJson->attributes);
                    $prodJson->amount = $prodJson->details->amount;
                    unset($prodJson->details);

                    return $prodJson;
                },
            );

            return response()->json([
                'total' => $cartPrice,
                'items' => $cartJson,
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
    public function getProduct($productId) {
        return Product::find($productId);
    }
}
