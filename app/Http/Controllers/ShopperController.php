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
            'name' => 'required|string|max:100',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email,'.Auth::id(),
            'password' => 'nullable|string|min:6|max:255|confirmed',
            'phone_number' => 'nullable|digits:9|integer',
            'nif' => 'nullable|integer|digits:9',
            'about_me' => 'nullable|string'
        ], [], [
            'password'  => 'new password',
            'name'  => 'name',
            'email'  => 'email',
            'phone_number'  => 'phone number',
            'nif'  => 'NIF',
            'about_me'  => 'About Me',
        ])->validate();
    }

    private function validateProfilePicture($file) {
        return Validator::make(['profile-picture' => $file], [
            'profile-picture' => 'file|image'
        ], [], [
            'profile-picture'  => 'profile picture',
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
            $response = [];
            $response["errors"] = [
                "cur-password" => "Current Password does not match our records"
            ];

            return response()->json($response, 403);
        }

        $user_attrs =
        [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ];

        $shopper_attrs = [
            'about_me' => $request->input("about-me"),
            'nif' => $request->input("nif"),
            'phone_number' => $request->input("phone-number"),
        ];

        $data_validate = array_merge($user_attrs, $shopper_attrs);
        $this->validateData($data_validate);

        if(array_key_exists('nif', $shopper_attrs) && !is_null($shopper_attrs['nif'])) {
            $nif_check = DB::select('SELECT check_nif(?)', [$shopper_attrs['nif']])[0]->check_nif;
            if($nif_check === '') {
                $response = [];
                $response["errors"] = [
                    "nif" => "NIF is not valid."
                ];

                return response()->json($response, 422);
            }
        }

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

    public function getAuth() {
        return redirect("/users/" . strval(Auth::id()));
    }
}
