<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Models\Address;
use App\Models\Shopper;
use App\Models\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use stdClass;

class AddressController extends Controller {

    private function validateGet(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        return Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
        ]);
    }

    private function validateCreate(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        return Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
            'street' => 'required|string|min:5',
            'door' => 'required|integer|min:1',
            'zip_code_id' => 'required|integer|min:1|exists:zip_code,id'
        ], [], [
            'zip_code_id' => 'zip code id'
        ]);
    }

    private function validateEdit(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        return Validator::make($request->all(), [
            'id' => 'integer|min:1|exists:authenticated_shopper,id',
            'address_id' => 'required|integer|min:1|exists:address,id',
            'street' => 'string|min:5',
            'door' => 'integer|min:1',
            'zip_code_id' => 'min:1|exists:zip_code,id'
        ], [], [
            'zip_code_id' => 'zip code id'
        ]);
    }

    private function validateRemove(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        return Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
            'address_id' => 'required|integer|min:1|exists:address,id',
        ]);
    }

    private function validateZipCode(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:4',
        ]);
    }

    /**
     * Verifies if a given address belongs to the shopper
     * 
     * @return boolean
     */
    private function addressInShopper(Address $address, Shopper $shopper) {
        return $shopper->addresses->contains($address);
    }

    /**
     * Retrieves the addresses associated to a user
     * 
     * @return array
     */
    private function retrieveAddresses(Shopper $shopper) {
        return $shopper->addresses->map(
            function ($addr) {
                $address = $addr->serialize();
                return $address;
            }
        );
    }

    /**
     * Gets the addresses a user has registred
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $id) {
        if (($v = $this->validateGet($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $shopper = Shopper::find($id);
        $addresses = $this->retrieveAddresses($shopper);
        return response($addresses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {
        if (($v = $this->validateCreate($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        if (
            !Auth::user()->is_admin &&
            !Hash::check($request->input("password"), Auth::user()->password)
        ) {
            $response = [];
            $response["errors"] = [
                "password" => "Current password does not match our records"
            ];

            return response()->json($response, 403);
        }

        $shopper = Shopper::find($id);

        $address = Address::create([
            "street" => $request->street,
            "door" => $request->door,
            "zip_code_id" => $request->zip_code_id,
        ]);

        $shopper->addresses()->attach($address->id);

        return response($address->serialize());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id) {
        if (($v = $this->validateEdit($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        if (
            !Auth::user()->is_admin &&
            !Hash::check($request->input("password"), Auth::user()->password)
        ) {
            $response = [];
            $response["errors"] = [
                "password" => "Current password does not match our records"
            ];

            return response()->json($response, 403);
        }

        $shopper = Shopper::find($id);
        $address = Address::find($request->address_id);

        if (!$this->addressInShopper($address, $shopper)) {
            return ApiError::addressNotInUser();
        }

        $address->update([
            "street" => $request->street ?? $address->street,
            "door" => $request->door ?? $address->door,
            "zip_code_id" => $request->zip_code_id ?? $address->zip_code_id,
        ]);

        return response($address->serialize());
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request, $id) {
        if (($v = $this->validateRemove($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        if (
            !Auth::user()->is_admin &&
            !Hash::check($request->input("password"), Auth::user()->password)
        ) {
            $response = [];
            $response["errors"] = [
                "password" => "Current password does not match our records"
            ];

            return response()->json($response, 403);
        }

        $addressId = $request->address_id;
        $shopper = Shopper::find($id);
        $address = Address::find($addressId);

        if (!$this->addressInShopper($address, $shopper)) {
            return ApiError::addressNotInUser();
        }

        $shopper->addresses()->detach($addressId);

        $address->delete();

        $addresses = $this->retrieveAddresses($shopper->fresh());
        return response($addresses);
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

        $serialized = $query->map(function ($zip) {
            $zipJson = [];
            $zipJson['id'] = $zip->id;
            $zipJson['county'] = $zip->county->name;
            $zipJson['district'] = $zip->district->name;
            return $zipJson;
        });

        return $serialized;
    }
}
