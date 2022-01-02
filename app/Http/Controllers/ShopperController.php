<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ShopperController extends Controller {

    public function getOrders() {
        
        $response = Gate::inspect('viewOrders', Shopper::class);

        if($response->denied()) abort(404, $response->message());

        $user = Auth::user();

        $shopper = Shopper::find($user->id);

        return view('pages.profile', ['shopper' => $shopper, 'page' => 'showShopperOrders']);
    }
}
