<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public static function getPossibleStatus() {
        $records = DB::select("SELECT unnest(enum_range(NULL::order_state))");

        foreach ($records as $key => $value) {
            $records[$key] = $value->unnest;
        }

        return $records;
    }

    public function update(Request $request, int $id) {
        return response()->json(
            ["updated-order" => Order::find($id)],
        200);
    }
}
