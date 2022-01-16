<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Exceptions\UnexpectedErrorLogger;
use App\Models\Product;
use App\Models\Shopper;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
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

    public function redirect() {
        return redirect(route('getWishlist', ['id' => Auth::id()]));
    }

    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function show(Request $request) {
        $userId = $request->route("id");

        $user = User::findOrFail($userId);
        $shopper = Shopper::findOrFail($userId);

        return view('pages.profile', ['shopper' => $shopper, 'page' => 'wishlist']);
    }

    /**
     * Returns a validator to update cart function
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorAdd(Request $request) {
        return Validator::make([
            'product_id' => $request->product_id
        ], [
            'product_id' => 'required|integer|min:1|exists:product,id',
        ], [], [
            'product_id' => 'product id'
        ]);
    }

    /**
     * Returns a validator to update cart function
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorDelete(Request $request) {
        return Validator::make([
            'product_id' => $request->route("product_id")
        ], [
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

        if (($v = $this->validatorAdd($request))->fails()) {
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
        $productId = $request->route('product_id');
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
