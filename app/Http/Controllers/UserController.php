<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use App\Models\User;
use App\Events\ProfileEdited;
use App\Models\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller {

    /**
     * Validates a user password or admin
     */
    public static function validateUserPasswordOrAdmin($user, $password) {
        if (
            !$user->is_admin &&
            !Hash::check($password, $user->password)
        ) {
            $response = [];
            $response["errors"] = [
                "password" => "Current password does not match our records"
            ];

            return response()->json($response, 403);
        }
    }

    /**
     * Shows the user for a given id.
     *
     * @param  int  $id
     * @return Response
     */
    public function showProfile($id) {

        $user = User::findOrFail($id);
        if ($user->is_deleted) {
            abort(404);
        }


        $response = Gate::inspect('viewProfile', [User::class, $user]);

        if ($response->denied()) abort(404, $response->message());

        $shopper = Shopper::find($id);
        if (!$shopper) {
            if (Auth::user()->id == $id) return redirect("users/" . strval(Auth::user()->id) . "/private");
            else return redirect("users/" . strval(Auth::user()->id));
        }
        return view('pages.profile', ['shopper' => $shopper, 'admin' => null, 'page' => 'aboutShopper']);
    }

    /**
     * Validates user form data
     *
     * @param Array $data The data to be validated
     * @return Array
     */
    private function validateDataUser($data) {
        return Validator::make($data, [
            'id' => 'exists:users,id',
            'name' => 'required|string|max:100',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users,email,' . $data["id"],
            'password' => 'nullable|string|min:6|max:255|confirmed'
        ], [], [
            'id' => 'ID',
            'password'  => 'new password',
            'name'  => 'name',
            'email'  => 'email'
        ])->validate();
    }

    /**
     * Validates shopper form data
     *
     * @param Array $data The data to be validated
     * @return Array
     */
    private function validateDataShopper($data) {
        return Validator::make($data, [
            'phone_number' => 'nullable|digits:9|integer',
            'nif' => 'nullable|integer|digits:9',
            'about_me' => 'nullable|string'
        ], [], [
            'phone_number'  => 'phone number',
            'nif'  => 'NIF',
            'about_me'  => 'About Me',
        ])->validate();
    }

    /**
     * Validates profile picture, checking if it is a file and an image
     *
     * @param  \Illuminate\Http\UploadedFile $file The file being validated
     * @return Array
     */
    private function validateProfilePicture($file) {
        return Validator::make(['profile-picture' => $file], [
            'profile-picture' => 'file|image'
        ], [], [
            'profile-picture'  => 'profile picture',
        ])->validate();
    }

    /**
     * Edits the shopper's data
     *
     * @param Request $request The request
     * @param int $id The shopper's id
     *
     * @return Response 200 if OK.
     */
    public function edit(Request $request, int $id) {

        $user = User::find($id);
        if ($user->is_deleted) {
            abort(404);
        }

        $this->authorize('update', [User::class, $user]);

        if (!Hash::check($request->input("cur-password"), Auth::user()->password)) { // check own (owner or admin) password
            $response = [];
            $response["errors"] = [
                "cur-password" => "Current password does not match our records"
            ];

            return response()->json($response, 403);
        }

        $user_attrs =
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'password_confirmation' => $request->input("password-confirmation"),
                'id' => $id,
            ];

        $shopper_attrs = null;
        $user = User::find($id);

        if (!$user->is_admin)
            $shopper_attrs = [
                'about_me' => $request->input("about-me"),
                'nif' => $request->input("nif"),
                'phone_number' => $request->input("phone-number"),
            ];

        $this->validateDataUser($user_attrs);
        if ($shopper_attrs) $this->validateDataShopper($shopper_attrs);

        $shopper = Shopper::find($id);

        if ($shopper_attrs) {

            if (array_key_exists('nif', $shopper_attrs) && !is_null($shopper_attrs['nif'])) {
                $nif_check = DB::select('SELECT check_nif(?)', [$shopper_attrs['nif']])[0]->check_nif;
                if ($nif_check === '') {
                    $response = [];
                    $response["errors"] = [
                        "nif" => "NIF is not valid."
                    ];

                    return response()->json($response, 422);
                }
            }
        }

        if (!is_null($profile = $request->file("profile-picture"))) {
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

        if ($request->password != "") {
            $user_attrs["password"] = bcrypt($request->password);
        } else {
            unset($user_attrs["password"]);
        }

        try {
            DB::beginTransaction();

            $user->update($user_attrs);
            if ($shopper)
                $shopper->update($shopper_attrs);

            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();

            return abort(406, "Unexpected Error");
        }

        $user = $user->fresh();
        $shopperData = $shopper ? $shopper->fresh()->toArray() : [];

        if (Auth::user()->is_admin && !$user->is_admin) {
            $not = new Notification();
            $not->shopper = $user->id;
            $not->type = "account";
            $not->account_mng_notif_type = "edited";
            $not->save();

            event(new ProfileEdited($user->id));
        }

        return response(
            array_merge(
                $user->fresh()->toArray(),
                ['photo' => $user->photo->url],
                $shopperData,
            ),
            200
        );
    }

    public function delete(Request $request, int $id) {
        $user = User::find($id);

        $this->authorize('delete', [User::class, $user]);

        if (!Hash::check($request->input("cur-password"), Auth::user()->password)) { // check own (owner or admin) password
            $response = [];
            $response["errors"] = [
                "cur-password" => "Current password does not match our records"
            ];

            return redirect()->back()->withErrors($response["errors"]);
        }

        Auth::logout();

        $user->delete();

        return redirect("join");
    }

    public function getAuthProfile() {
        return redirect(route('getUser', ['id' => Auth::id()]));
    }

    public function getEditPage($id) {

        $user = User::findOrFail($id);

        $this->authorize('update', [User::class, $user]);

        if ($user->is_deleted) {
            abort(404);
        }

        $admin = null;
        $shopper = Shopper::find($id);

        if (!$shopper && Auth::user()->id == $id) $admin = $user;
        return view('pages.profile', ['shopper' => $shopper, 'admin' => $admin, 'page' => 'editUser']);
    }

    /**
     * Search users according to filters in the query
     *
     * @return Response
     */
    public function list(Request $request) {
        $this->authorize('viewAny', User::class);

        $dc =  new DatatableController();
        return $dc->get($request, DB::table('user_shopper'));
    }
}
