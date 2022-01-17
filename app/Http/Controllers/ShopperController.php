<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiError;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use Exception;
use Facade\FlareClient\Api;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ShopperController extends Controller {

    public function getOrders() {

        $response = Gate::inspect('viewOrders', Shopper::class);

        if ($response->denied()) abort(404, $response->message());

        $user = Auth::user();

        $shopper = Shopper::find($user->id);

        return view('pages.profile', ['shopper' => $shopper, 'page' => 'showShopperOrders']);
    }

    public function getAddresses($id) {

        $shopper = Shopper::find($id);

        $this->authorize('viewUserAddresses', [Shopper::class, $shopper]);

        return view('pages.profile', ['shopper' => $shopper, 'page' => 'addresses']);
    }

    public function blockShopper(int $id) {
        $shopper = Shopper::findOrFail($id);

        $this->authorize('block', Shopper::class);

        if($shopper->is_blocked) {
            return ApiError::userAlreadyBlocked();
        }

        $shopper_attrs = [
            'is_blocked' => true,
        ];

        try {
            $shopper->update($shopper_attrs);
            $shopper->save();
        } catch(Exception $ex) {
            return ApiError::unexpected();
        }
        return response()->json(
            ["message" => 'User unblocked with success'],
            200
        );
    }

    public function unblockShopper(int $id) {
        $shopper = Shopper::findOrFail($id);

        $this->authorize('block', Shopper::class);
        
        if(!$shopper->is_blocked) {
            return ApiError::userNotBlocked();
        }
        
        $shopper_attrs = [
            'is_blocked' => false,
        ];

        try {
            $shopper->update($shopper_attrs);

        } catch(QueryExecuted $ex) {
            return ApiError::unexpected();
        }
        return response()->json(
            ["message" => 'User unblocked with success'],
            200
        );
    }
}
