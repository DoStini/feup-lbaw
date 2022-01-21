<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiError;
use App\Http\Controllers\Controller;
use App\Mail\RecoverAccount;
use App\Models\RecoverUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RecoverAccountController extends Controller {

    /**
     * Shows the recover account page
     *
     * @return Response
     */
    public function showEmailForm() {
        return view('auth.recover');
    }

    private function validateRecoverRequest(Request $request) {
        return Validator::make(
            $request->all(),
            [
                'email' => 'required|string|min:1,max:255|exists:users,email',
            ]
        );
    }

    private function validTimestamp($model) {
        $tolerance = Config::get('constants.constants.auth.recover_link_expire');
        $timeDiff = Carbon::now()->diffInMinutes($model->timestamp);

        return $timeDiff < $tolerance;
    }

    private function validateFinishRecoverRequest(Request $request) {
        return Validator::make(
            ['token' => $request->token],
            [
                'token' => 'required|string|min:32,max:32|exists:recover_users,token',
            ]
        );
    }

    private function validateNewPassword($request) {
        return Validator::make($request->all(), [
            'token' => 'required|string|min:32,max:32|exists:recover_users,token',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function getFinishRecoverPage(Request $request) {
        if ($this->validateFinishRecoverRequest($request)->fails()) {
            return view('errors.invalidtoken');
        }

        $token = $request->token;

        $model = RecoverUser::where("token", "=", $token)->first();

        if (!$this->validTimestamp($model)) {
            return view('errors.invalidtoken');
        }

        return view('auth.password', ['token' => $token]);
    }

    public function finishRecoverRequest(Request $request) {
        $this->validateNewPassword($request)->validateWithBag('new_password_form');

        $token = $request->token;
        $model = RecoverUser::where("token", "=", $token)->get()[0];

        if (!$this->validTimestamp($model)) {
            return view('errors.invalidtoken');
        }

        $password = bcrypt($request->password);

        $user = User::where("email", "=", $model->email);
        $user->update([
            'password' => $password,
        ]);

        $model->delete();

        return redirect('join');
    }

    public function submitRecoverRequest(Request $request) {

        if (!$this->validateRecoverRequest($request)->fails()) {
            $email = $request->email;

            $token = md5(uniqid($email) . strval(Carbon::now()->timestamp));

            if ($old = RecoverUser::where("email", "=", $email)) {
                $old->delete();
            }

            $model = new RecoverUser();
            $model->email = $request->email;
            $model->token = $token;
            $model->save();

            $query = User::where("email", "=", $email);
            if ($query->count() == 0) {
                return response("");
            }

            $user = $query->first();

            Mail::to($request->email)->send(new RecoverAccount($user, $token));
        }

        return response("");
    }
}
