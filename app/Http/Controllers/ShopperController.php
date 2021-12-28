<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class ShopperController extends Controller {

    /**
     * Shows all users.
     *
     * @return Response
     */
    public function list() {
        if (!Auth::check()) return redirect('/login');
        $this->authorize('list', Card::class);
        $cards = Auth::user()->cards()->orderBy('id')->get();
        return view('pages.cards', ['cards' => $cards]);
    }

    /**
     * Shows cart contents
     *
     * @return Response
     */
    public function getCart() {
        if (!Auth::check()) return redirect('/login');
        $user = Auth::user();
        if ($user->is_admin) return redirect('/login');

        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;

        return view('pages.cart', ['cart' => $cart]);
    }

    public function getOrders() {
        if (!Auth::check()) return redirect('/login');
        $user = Auth::user();
        if ($user->is_admin) return redirect('/login');

        $shopper = Shopper::find($user->id);

        return view('pages.profile', ['shopper' => $shopper, 'page' => 'showShopperOrders']);
    }
}
