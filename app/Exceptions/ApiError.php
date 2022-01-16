<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Config;

class ApiError {

    /**
     * Returns a json response with an error regarding validators
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function validatorError($fieldErrors) {
        $err = Config::get('constants.fields');
        return ApiError::generateErrorMessage($err, [
            'errors' => $fieldErrors,
        ]);
    }

    /**
     * Returns a json response with an error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function couponNotActive() {
        $err = Config::get('constants.coupon.not_active');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function couponActive() {
        $err = Config::get('constants.coupon.active');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function productNotInWishlist() {
        $err = Config::get('constants.wishlist.not_exists');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function productAlreadyInWishlist() {
        $err = Config::get('constants.wishlist.already_exists');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error regarding unavailable products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function notEnoughStock() {
        $err = Config::get('constants.cart.stock');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error regarding admin logged in
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function mustBeShopper() {
        $err = Config::get('constants.authentication.must_be_shopper');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function productDoesNotExist() {
        $err = Config::get('constants.not_exist_product');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function addressNotInUser() {
        $err = Config::get('constants.address.not_in_user');
        return ApiError::generateErrorMessage($err);
    }


    /**
     * Returns a json response with 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function userAlreadyBlocked() {
        $err = Config::get('constants.blocked.already_blocked');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function userNotBlocked() {
        $err = Config::get('constants.blocked.not_blocked');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error regarding user not authenticated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function auth() {
        $err = Config::get('constants.authentication.auth');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an unexpected error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function unexpected() {
        $err = Config::get('constants.unexpected');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function generateErrorMessage($obj, $extra = null) {
        try {
            return response()->json(
                array_merge(
                    ['message' => $obj['message']],
                    $extra ?? [],
                ),
                $obj['code'],
            );
        } catch (\Exception $_) {
            return ApiError::unexpected();
        }
    }
}
