<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Models\Address;
use App\Models\Shopper;
use App\Models\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'street' => 'required|string',
            'door' => 'required|integer|min:1',
            'zip_code_id' => 'required|min:1|exists:zip_code,id'
        ], [], [
            'zip_code_id' => 'zip code id'
        ]);
    }

    private function validateDelete() {
        return Validator::make($request->all(), [
            'address-id' => 'required|integer|min:1|exists:address,id',
        ]);
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
        $addresses = $shopper->addresses->map(
            function ($addr) {
                $address = $addr->serialize();
                return $address;
            }
        );
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
    public function edit(Address $address) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address) {
        //
    }
}
