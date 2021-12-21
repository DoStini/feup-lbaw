<?php

namespace App\Exceptions;

use Illuminate\Support\Facades\Config;

class ApiError {

    /**
     * Returns a json response with an error regarding admin logged in
     *
     * @return void
     */
    public static function cantBeAdmin() {
        $err = Config::get('constants.authentication.cant_be_admin');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error regarding user not authenticated
     *
     * @return void
     */
    public static function auth() {
        $err = Config::get('constants.authentication.auth');
        return ApiError::generateErrorMessage($err);
    }

    public static function unexpected() {
        $err = Config::get('constants.unexpected');
        return ApiError::generateErrorMessage($err);
    }

    /**
     * Returns a json response with an error
     *
     * @return void
     */
    public static function generateErrorMessage($obj) {
        try {
            return response()->json(
                ['message' => $obj['message']],
                $obj['code'],
            );
        } catch (\Exception $_) {
            return ApiError::unexpected();
        }
    }
}
