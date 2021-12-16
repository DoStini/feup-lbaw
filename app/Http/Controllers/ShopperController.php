<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

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
            'password' => 'string|min:6|confirmed',
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

        if(!Hash::check($request->input("cur-password"), Auth::user()->password)) {
            return abort(403, "Wrong password");
        }

        $user_attrs =
        [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if(!is_null($request->password) && $request->password !== "") {
            $user_attrs["password"] = $request->password;
            $user_attrs["password_confirmation"] = $request->password_confirmation;
        }

        $shopper_attrs = array_filter([ // choose which parameters to validate (filters empty)
            'about_me' => $request->input("about-me"),
            'nif' => $request->input("nif"),
            'phone_number' => $request->input("phone-number"),
        ]);

        $this->validateData($user_attrs);
        $this->validateData($shopper_attrs);

        $shopper_attrs = [ // choose which parameters are updated (can be empty)
            'about_me' => $request->input("about-me"),
            'nif' => $request->input("nif"),
            'phone_number' => $request->input("phone-number"),
        ];

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

        return response("Profile Edited Successfully!", 200);
    }
}
