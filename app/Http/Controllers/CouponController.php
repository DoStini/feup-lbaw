<?php

namespace App\Http\Controllers;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function show(Coupon $coupon) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Coupon $coupon) {
        //
    }
}
