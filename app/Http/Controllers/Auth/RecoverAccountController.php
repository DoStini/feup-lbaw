<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\ApiError;
use App\Http\Controllers\Controller;
use App\Models\RecoverUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
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
                'token' => 'required|string|min:64,max:64|exists:recover_users,token',
            ]
        );
    }

    public function getFinishRecoverPage(Request $request) {
        if ($this->validateFinishRecoverRequest($request)->fails()) {
            return view('auth.invalidtoken');
        }

        $token = $request->token;

        $model = RecoverUser::where("token", "=", $token)->get()[0];

        if (!$this->validTimestamp($model)) {
            return view('auth.invalidtoken');
        }

        return view('auth.password', ['token' => $token]);
    }

    public function submitRecoverRequest(Request $request) {

        if (!$this->validateRecoverRequest($request)->fails()) {
            $token = Str::random(64);
            $email = $request->email;

            if ($old = RecoverUser::where("email", "=", $email)) {
                $old->delete();
            }

            $model = new RecoverUser();
            $model->email = $request->email;
            $model->token = $token;
            $model->save();

            return response($model);
        }

        return response("");
    }
}
