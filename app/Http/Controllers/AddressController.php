<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Shopper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller {

    private function validateCreate() {
        return Validator::make($request->all(), [
            'street' => 'required|string|min:5',
            'door' => 'required|integer|min:1',
            'zip_code_id' => 'required|integer|min:1|exists:zip_code'
        ], [], [
            'zip_code_id' => 'zip code id'
        ]);
    }

    private function validateEdit() {
        return Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:address',
            'street' => 'required|string',
            'door' => 'required|integer|min:1',
            'zip_code_id' => 'required|min:1|exists:zip_code'
        ], [], [
            'zip_code_id' => 'zip code id'
        ]);
    }

    private function validateDelete() {
        return Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:address',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        if (($v = $this->validateCreate($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $shopper = Shopper::find(Auth::user()->id);

        $address = Address::create([
            "street" => $request->street,
            "door" => $request->door,
            "zip_code_id" => $request->zip_code_id,
        ]);

        $shopper->address()->attach($address->id);

        return response($address);
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
