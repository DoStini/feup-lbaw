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
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Type\Integer;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CartController extends Controller {

    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function show() {

        $response = Gate::inspect('viewCart', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        $user = Auth::user();

        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;
        $cartTotal = $this->cartPrice($cart);

        return view('pages.cart', ['cart' => $cart, 'cartTotal' => $cartTotal, 'user' => $user]);
    }

    /**
     * Returns a validator to the functions that only require a product id
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorDelete($request) {
        return Validator::make(
            [
                'product_id' => $request->route('id'),
            ],
            [
                'product_id' => 'required|integer|min:1|exists:product,id',
            ],
            ['product_id' => 'product id']
        );
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
     * Returns a validator to update cart function
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validatorAdd(Request $request) {
        return Validator::make($request->all(), [
            'product_id' => 'required|integer|min:1|exists:product,id',
            'amount' => 'integer|min:1'
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
     * Parses a cart collection into the desired model of response body
     *
     * @return array
     */
    private function cartToJson($cart) {
        return $cart->map(
            function ($product) {
                $prodJson = $product->serialize();
                $prodJson->amount = $prodJson->details->amount;
                unset($prodJson->details);
                return $prodJson;
            },
        );
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

        $response = Gate::inspect('updateCart', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

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

        $cart = $shopper->fresh()->cart;
        $cartPrice = $this->cartPrice($cart);
        $cartJson = $this->cartToJson($cart);

        return response()->json([
            'total' => $cartPrice,
            'items' => $cartJson,
        ]);
    }

    /**
     * Adds products to the user's cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request) {

        $response = Gate::inspect('updateCart', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        if (($v = $this->validatorAdd($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $amount = $request->amount ?? 1;
        $productId = $request->product_id;
        $product = Product::find($productId);
        $shopper = Shopper::find($userId);

        if ($this->productInCart($shopper, $product)) {
            $newAmount = $amount + $shopper->cart()->find($productId)->details->amount;
            if (!$this->validStock($product, $newAmount)) {
                return ApiError::notEnoughStock();
            }

            $shopper->cart()->updateExistingPivot($productId, ['amount' => $newAmount]);
        } else {
            if (!$this->validStock($product, $amount)) {
                return ApiError::notEnoughStock();
            }

            $shopper->cart()->attach($productId, ['amount' => $amount]);
        }

        $cart = $shopper->fresh()->cart;
        $cartPrice = $this->cartPrice($cart);
        $cartJson = $this->cartToJson($cart);

        return response()->json([
            'total' => $cartPrice,
            'items' => $cartJson,
        ]);
    }

    /**
     * Deletes a product from the user's cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request) {

        $response = Gate::inspect('deleteFromCart', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        if (($v = $this->validatorDelete($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $userId = Auth::user()->id;
        $productId = $request->route('id');
        $product = Product::find($productId);
        $shopper = Shopper::find($userId);

        if (!$this->productInCart($shopper, $product)) {
            return ApiError::productNotInCart();
        }

        $shopper->cart()->detach($productId);

        $cart = $shopper->fresh()->cart;
        $cartPrice = $this->cartPrice($cart);
        $cartJson = $this->cartToJson($cart);

        return response()->json([
            'total' => $cartPrice,
            'items' => $cartJson,
        ]);
    }

    /**
     * Gets the list of products, their corresponding amount and the cart value of a user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get() {

        $response = Gate::inspect('viewCart', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        try {
            $user = Auth::user();
            $shopper = Shopper::find($user->id);
            $cart = $shopper->cart;
            $cartPrice = $this->cartPrice($cart);

            $cartJson = $this->cartToJson($cart);

            return response()->json([
                'total' => $cartPrice,
                'items' => $cartJson,
            ]);
        } catch (Exception $err) {
            UnexpectedErrorLogger::log($err);
            return ApiError::unexpected();
        }
    }

    public function checkoutPage() {

        $user = Auth::user();
        $shopper = Shopper::findOrFail($user->id);

        $response = Gate::inspect('viewCheckout', [Shopper::class, $shopper]);

        if ($response->denied()) abort(404, $response->message());

        $cart = $shopper->cart;

        $cartTotal = $this->cartPrice($cart);

        return view("pages.checkout", ["cart" => $cart, "shopper" => $shopper, "cartTotal" => $cartTotal]);
    }

    /**
     *
     * @param Array $data
     * @param Shopper $shopper
     */
    private function getCheckoutValidator($data, $addresses) {
        return Validator::make($data, [
            "address-id" => [
                "required",
                "min:1",
                "exists:address,id",
                "integer",
                Rule::in($addresses)
            ],
            "coupon-id" => "nullable|exists:coupon,id|integer|min:1",
            "payment-type" => "required|string|in:paypal,bank"
        ], [], [
            "address-id" => "address",
            "coupon-id" => "coupon",
            "payment-type" => "payment type",
        ]);
    }

    private function validateCoupon($coupon, $cart_price) {
        if (!$coupon->is_active) {
            return redirect()->back()->withErrors(['coupon-id' => 'The selected coupon is not active.'])->withInput();
        }

        if ($coupon->minimum_cart_value > $cart_price) {
            return redirect()->back()->withErrors(['coupon-id' => "The cart's total cost does not meet the selected coupon's minimum cart cost."])->withInput();
        }
    }

    private function validateStock($cart) {
        $products = [];

        foreach ($cart as $product) {
            if ($product->details->amount > $product->stock) {
                array_push($products, $product);
            }
        }

        $products = array_map(function ($entry) {
            $entry->photos = array_map(function ($photo) {
                return $photo['url'];
            }, $entry->photos->toArray());
            $entry->attributes = json_decode($entry->attributes);
            return $entry;
        }, $products);
        //dd($products);
        if (!empty($products)) return redirect()->back()->withErrors(['cart' => "At least one of the cart's products doesn't have enough stock.", 'products' => $products])->withInput();
    }

    private function validateCheckoutData(Request $request) {
        $user = Auth::user();
        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;

        if ($cart->isEmpty()) {
            return redirect()->back()->withErrors(["cart" => "The cart is empty."])->withInput();
        }

        $addressesID = array_map(fn ($address): int => $address["id"], $shopper->addresses->toArray());

        $validator = $this->getCheckoutValidator($request->all(), $addressesID);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->has("coupon-id") && !is_null($request->input("coupon-id"))) {
            $cart_price = $this->cartPrice($cart);

            $coupon = Coupon::find($request->input("coupon-id"));
            $result = $this->validateCoupon($coupon, $cart_price);
            if (!is_null($result)) {
                return $result;
            }
        }

        $result = $this->validateStock($cart);
        if (!is_null($result)) {
            return $result;
        }
    }

    private function payPalPayment($order_id) {
        $provider = new PayPalClient();
        $provider->setApiCredentials(Config::get('paypal'));
        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('confirmPaypal', ['id' => $order_id]),
                "cancel_url" => route('confirmPaypal', ['id' => $order_id])
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "1000.00"
                    ]
                ]
            ]
        ];

        $accessToken = $provider->getAccessToken();

        $order = $provider->createOrder($data);

        foreach ($order['links'] as $link) {
            if ($link['rel'] == 'approve') {
                $redirect_url = $link['href'];
                break;
            }
        }

        return [
            'order' => $order,
            'redirect' => $redirect_url
        ];
    }

    private function addPayment(Request $request, $order_id) {
        $order = Order::find($order_id);

        $this->authorize('create', [Payment::class, $order]);

        $payment = new Payment;
        $payment->order_id = $order_id;

        if ($request->input("payment-type") == 'bank') {
            $payment->entity = "12345";
            $payment->reference = rand(10, 10000);
        } else {
            $paypal = $this->payPalPayment($order_id);
            $payment->paypal_transaction_id = $paypal['order']['id'];
        }

        $payment->value = $order->total;
        $payment->save();

        return $paypal;
    }

    public function checkout(Request $request) {

        $this->authorize('create', Order::class);

        $result = $this->validateCheckoutData($request);
        if (!is_null($result)) {
            return $result;
        }

        $addressID = $request->input("address-id");
        $couponID = $request->input("coupon-id");

        try {
            DB::beginTransaction();
            DB::unprepared("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");

            DB::statement("CALL create_order(?, ?, ?);", [Auth::user()->id, $addressID, $couponID]);

            $order_id = DB::select("SELECT currval(pg_get_serial_sequence('order','id'));")[0]->currval;
            $paypal = $this->addPayment($request, $order_id);

            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors(["order" => "Unexpected Error"])->withInput();
        }

        if ($request->input("payment-type") == 'bank') {
            return redirect(route('orders', ['id' => $order_id]));
        } else {
            return redirect()->away($paypal['redirect']);
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
