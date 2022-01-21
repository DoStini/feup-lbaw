<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    private function validateManage(Request $request) {
        return Validator::make([
            'id' => $request->route('id')
        ], [
            'id' => 'required|min:1|exists:coupon,id'
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
        $coupon->code = strtoupper($request->code);
        $coupon->percentage = round($request->percentage / 100, 4);
        $coupon->minimum_cart_value = $request->minimum_cart_value;

        if (isset($request->is_active)) {
            $coupon->is_active = $request->is_active == "on" ? true : false;
        }

        $coupon->save();

        return redirect(route('getCouponDashboard'));
    }

    /**
     * Disables a coupon
     *
     * @return \Illuminate\Http\Response
     */
    public function disable(Request $request) {
        $this->authorize('manageCoupon', Coupon::class);

        if (($v = $this->validateManage($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }


        $coupon = Coupon::findOrFail($request->route("id"));

        if (!$coupon->is_active) {
            return ApiError::couponNotActive();
        }

        $coupon->is_active = false;
        $coupon->save();

        return response("");
    }

    /**
     * Show a coupon
     *
     * @return \Illuminate\Http\Response
     */
    public function enable(Request $request) {
        $this->authorize('manageCoupon', Coupon::class);

        if (($v = $this->validateManage($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $coupon = Coupon::findOrFail($request->route("id"));

        if ($coupon->is_active) {
            return ApiError::couponActive();
        }

        $coupon->is_active = true;
        $coupon->save();

        return response("");
    }

    private function validateList(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:1|max:20',
            'min' => 'numeric',
        ]);
    }

    private function validateVerify(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:1|max:20|exists:coupon,code',
            'total' => 'required|numeric',
        ]);
    }

    /**
     * Validates a coupon
     */
    public function verifyCoupon(Request $request) {
        if (($v = $this->validateVerify($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $coupon = Coupon::where("code", "=", $request->code)->first();

        if ($coupon->minimum_cart_value > $request->total) {
            return ApiError::couponInvalid();
        }

        return $coupon;
    }

    /**
     * Search users according to filters in the query
     *
     * @return Response
     */
    public function list(Request $request) {
        $this->authorize('viewAny', User::class);

        $dc =  new DatatableController();
        return $dc->get($request, DB::table('coupon_view'));
    }
}
