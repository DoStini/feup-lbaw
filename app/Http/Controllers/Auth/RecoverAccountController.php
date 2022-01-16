<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\RecoverUser;
use Illuminate\Http\Request;
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

    public function submitRecoverRequest(Request $request) {
        $email = $request->email;
        $err = $this->validateRecoverRequest($request)->validate();

        if (!isset($err['errors'])) {
            $token = Str::random(64);
            $email = $request->email;

            if ($old = RecoverUser::where("email", "=", $email)) {
                $old->delete();
            }

            $model = new RecoverUser();
            $model->email = $request->email;
            $model->token = $token;
            $model->save();

            // return response($model);
        }

        return response("");
    }
}
