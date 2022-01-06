<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Exceptions\UnexpectedErrorLogger;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Shopper;
use App\Policies\CartPolicy;
use ErrorException;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Type\Integer;

class WishlistController extends Controller {

    /**
     * Parses a wishlist collection into the desired model of response body
     *
     * @return array
     */
    private function wishlistToJson($wishlist) {
        return $wishlist->map(
            function ($product) {
                $prodJson = $product->serialize();
                unset($prodJson->pivot);
                return $prodJson;
            },
        );
    }

    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function show() {

        $response = Gate::inspect('manageWishlist', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        $user = Auth::user();

        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;
        $cartTotal = $this->cartPrice($cart);

        return view('pages.cart', ['cart' => $cart, 'cartTotal' => $cartTotal, 'user' => $user]);
    }

    /**
     * Returns a validator to update cart function
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validator(Request $request) {
        return Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1|exists:product,id',
        ], [], [
            'product_id' => 'product id'
        ]);
    }

    /**
     * Verifies if a product is in the user's cart
     *
     * @param Collection
     * @param Integer
     * @return float
     */
    private function productInWishlist($shopper, $product) {
        return $shopper->wishlist->contains($product);
    }

    /**
     * Adds products to the user's wishlist
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request) {

        $response = Gate::inspect('manageWishlist', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        if (($v = $this->validator($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $productId = $request->product_id;

        $product = Product::find($productId);
        $shopper = Shopper::find($userId);

        if ($this->productInWishlist($shopper, $product)) {
            return ApiError::productAlreadyInWishlist();
        } else {
            $shopper->wishlist()->attach($productId);
        }

        $wishlist = $shopper->fresh()->wishlist;
        $wishlistJson = $this->wishlistToJson($wishlist);

        return response()->json($wishlistJson);
    }

    /**
     * Deletes a product from the user's cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {

        $response = Gate::inspect('manageWishlist', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        if (($v = $this->validatorDelete($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $productId = $request->route('id');
        $product = Product::find($productId);
        $shopper = Shopper::find($userId);

        if (!$this->productInWishlist($shopper, $product)) {
            return ApiError::productNotInWishlist();
        }

        $shopper->wishlist()->detach($productId);

        $wishlist = $shopper->fresh()->wishlist;
        $wishlistJson = $this->wishlistToJson($wishlist);

        return response()->json($wishlistJson);
    }

    /**
     * Gets the list of products, their corresponding amount and the cart value of a user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get() {
        $response = Gate::inspect('manageWishlist', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        try {
            $user = Auth::user();
            $shopper = Shopper::find($user->id);
            $wishlist = $shopper->wishlist;
            $wishlistJson = $this->wishlistToJson($wishlist);

            return response()->json($wishlistJson);
        } catch (Exception $err) {
            UnexpectedErrorLogger::log($err);
            return ApiError::unexpected();
        }
    }
}
