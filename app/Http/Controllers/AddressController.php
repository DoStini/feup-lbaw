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
use Illuminate\Support\MessageBag;
use stdClass;

class AddressController extends Controller {

    private function validateGet(Request $request) {
        return Validator::make(['id' => $request->route('id')], [
            'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
        ]);
    }

    private function validateCreate(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        $request->merge(['address_id' => $request->route('address_id')]);
        return Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
            'street' => 'required|string|min:1|max:255',
            'door' => 'string|required|min:1|max:10',
            'zip_code_id' => 'required|integer|min:1|exists:zip_code,id'
        ], [], [
            'id' => 'ID',
            'zip_code_id' => 'zip code ID'
        ]);
    }

    private function validateEdit(Request $request) {
        $request->merge(['id' => $request->route('id')]);
        $request->merge(['address_id' => $request->route('address_id')]);
        return Validator::make($request->all(), [
            'id' => 'integer|min:1|exists:authenticated_shopper,id',
            'address_id' => 'required|integer|min:1|exists:address,id',
            'street' => 'string|min:1|max:255',
            'door' => 'string|min:1|max:10',
            'zip_code_id' => 'min:1|exists:zip_code,id'
        ], [], [
            'id' => 'id',
            'zip_code_id' => 'zip code ID'
        ]);
    }

    private function validateRemove(Request $request) {
        return Validator::make(
            [
                'id' => $request->route('id'),
                'address_id' => $request->route('address_id')
            ],
            [
                'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
                'address_id' => 'required|integer|min:1|exists:address,id',
            ]
        );
    }

    private function validateZipCode(Request $request) {
        return Validator::make($request->all(), [
            'code' => 'required|string|min:3',
        ]);
    }


    /**
     * Retrieves the addresses associated to a user
     *
     * @return array
     */
    private function retrieveAddresses(Shopper $shopper) {

        return $shopper->addresses->map(
            function ($addr) {
                $address = $addr->aggregate();
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

        $shopper = Shopper::find($id);

        $this->authorize('view', $shopper);

        if (($v = $this->validateGet($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $addresses = $this->retrieveAddresses($shopper);
        return response($addresses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id) {

        $this->authorize('create');

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

        return response($address->aggregate());
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

        $addressId = $request->address_id;
        $address = Address::find($addressId);

        $this->authorize('update', $address);

        $address->update(array_filter([
            "street" => $request->street,
            "door" => $request->door,
            "zip_code_id" => $request->zip_code_id,
        ]));

        return response($address->aggregate());
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

        $addressId = $request->address_id;
        $address = Address::find($addressId);

        $this->authorize('delete', $address);

        $shopper = Shopper::find($id);

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
