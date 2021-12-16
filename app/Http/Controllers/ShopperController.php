<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


use App\Models\Shopper;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ShopperController extends Controller {
    /**
     * Shows the user for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $shopper = Shopper::find($id);
        return view('pages.shopper', ['shopper' => $shopper]);
    }

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
    public function cart() {
        if (!Auth::check()) return redirect('/login');
        $user = Auth::user();
        if ($user->is_admin) return redirect('/login');

        $shopper = Shopper::find($user->id);
        $cart = $shopper->cart;

        return view('pages.cart', ['cart' => $cart]);
    }

    /**
     *
     */
    public function edit(Request $request, int $id) {
        if(is_null($shopper = Shopper::find($id))) {
            return response('Shopper does not exist', 404);
        }

        $user_attrs = array_filter(
        [
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if(!is_null($profile = $request->file("profile-picture"))) {
            $path = $profile->storePubliclyAs(
                "images/user",
                "user" . $id . "-" . uniqid() . "." . $profile->extension(),
                "public"
            );

            $public_path = "/storage/" . $path;
            $photo = Photo::create(["url" => $public_path]);

            $user_attrs["photo_id"] = $photo->id;
        }

        if($request->password != "") {
            $user_attrs["password"] = bcrypt($request->password);
        }

        $user = $shopper->user;

        if(!empty($user_attrs)) {
            $user = $user->update($user_attrs);
        }

        return response($user, 200);
    }
}
