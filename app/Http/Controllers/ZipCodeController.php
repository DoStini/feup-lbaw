<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Validator;
use App\Models\ZipCode;

class ZipCodeController extends Controller {

    private function validateZipCode(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:3',
        ]);
    }

    /**
     * Retrieves possible postal codes for a given input
     */
    public function zipCode(Request $request) {
        if (($v = $this->validateZipCode($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $query = ZipCode
            ::where("zip_code", "LIKE", $request->code . "%")
            ->take(15)
            ->get();

        $aggregate = $query->map(function ($zip) {
            $zipJson = [];
            $zipJson['id'] = $zip->id;
            $zipJson['county'] = $zip->county->name;
            $zipJson['district'] = $zip->district->name;
            $zipJson['zip_code'] = $zip->zip_code;
            return $zipJson;
        });

        return $aggregate;
    }
}
