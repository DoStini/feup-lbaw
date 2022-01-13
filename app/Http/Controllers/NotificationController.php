<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Models\Shopper;
use App\Models\User;
use App\Events\CoolEvent;
use App\Events\ProfileEdited;
use App\Models\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

use Exception;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller {

    private function validateOp(Request $request) {
        return Validator::make(['id' => $request->route('id')], [
            'id' => 'required|integer|min:1|exists:authenticated_shopper,id',
        ]);
    }

    public function get(Request $request) {
        if (($v = $this->validateOp($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $id = $request->route('id');
        $skip = $request->skip ?? 0;
        $pageSize = 5;

        $shopper = Shopper::findOrFail($id);
        $notifications = $shopper->notifications()->orderBy('timestamp');
        $count = $notifications->count();
        $query = $notifications->skip($skip)->take($pageSize)->get();
        $newNotifications = $notifications->where('read', '=', 'false')->count();

        return response([
            'new_nots' => $newNotifications,
            'total' => $count,
            'notifications' => $query,
        ]);
    }

    public function clear(Request $request) {
        if (($v = $this->validateOp($request))->fails()) {
            return ApiError::validatorError($v->errors());
        }

        $notifications = Notification::where('shopper', '=', $request->route('id'));
        $notifications->update([
            'read' => true
        ]);

        return response([]);
    }
}
