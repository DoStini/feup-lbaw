<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use App\Models\User;
use Illuminate\Database\QueryException;
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

    private function validateData($data) {
        return Validator::make($data, [
            'name' => 'string|max:100',
            'email' => 'string|email|max:255',
            'password' => 'string|min:6',
            'phone_number' => 'digits:9|integer',
            'nif' => 'integer|digits:9',
            'about-me' => 'string'
        ])->validate();
    }

    private function validateProfilePicture($file) {
        return Validator::make(['profile-picture' => $file], [
            'profile-picture' => 'file|image'
        ])->validate();
    }

    /**
     *
     */
    public function edit(Request $request, int $id) {
        if(is_null($shopper = Shopper::find($id))) {
            return abort(404, 'Shopper does not exist');
        }

        $user_attrs =
        [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if(!is_null($request->password) && $request->password !== "") {
            $user_attrs["password"] = $request->password;
        }

        $shopper_attrs = [
            'about_me' => $request->input("about-me"),
            'nif' => $request->input("nif"),
            'phone_number' => $request->input("phone-number"),
        ];

        $this->validateData($user_attrs);
        $this->validateData($shopper_attrs);

        if(!is_null($profile = $request->file("profile-picture"))) {
            $this->validateProfilePicture($profile);

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
        } else {
            unset($user_attrs["password"]);
        }

        $user = $shopper->user;

        if(!empty($user_attrs)) {
            try {
                $user->update($user_attrs);
            } catch (QueryException $ex) {
                return abort(406, "User vars");
            }
        }

        if(!empty($shopper_attrs)) {
            try {
                $shopper->update($shopper_attrs);
            } catch (QueryException $ex) {
                return abort(406, "Shopper vars");
            }
        }

        return response(1, 200);
    }
}
