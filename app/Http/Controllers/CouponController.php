<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller {

    private function validateCreate(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:3|unique:coupon',
            'percentage' => 'required|numeric|min:0|max:100',
            'minimum_cart_value' => 'required|numeric|min:0',
            'is_active' => 'nullable|in:on,off',
        ], [], [
            'minimum_cart_value' => 'minimum cart value',
            'is_active' => 'is active',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $this->authorize('createCoupon', Coupon::class);

        $this->validateCreate($request)->validateWithBag('new_coupon');

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->percentage = $request->percentage;
        $coupon->minimum_cart_value = $request->minimum_cart_value;

        if (isset($request->is_active)) {
            $coupon->is_active = $request->is_active;
        }

        $coupon->save();

        return redirect(route('getCouponDashboard'));
    }
    private function validateList(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:1,max:20',
            'min' => 'numeric',
        ]);
    }

    /**
     * Retrieves possible coupons for a given input
     */
    public function list(Request $request) {
        if (($v = $this->validateList($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $query = Coupon
            ::where("code", "ILIKE", $request->code . "%")
            ->where("is_active", "=", "TRUE")
            ->where("minimum_cart_value", "<=", (@$request->min ?: 0))
            ->take(15)
            ->get();

        return $query;
    }
}
