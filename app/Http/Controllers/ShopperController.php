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
            return response('Shopper does not exist', 404);
        }

        $user_attrs = array_filter(
        [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $this->validateData($user_attrs);

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
            $user = $user->update($user_attrs);
        }

        return response($user, 200);
    }
}
