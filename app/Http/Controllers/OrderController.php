<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{   

    public function show($id) {

        $order = Order::find($id);

        $this->authorize('view', $order);

        $order = Order::leftJoin('users', 'order.shopper_id', '=', 'users.id')
                        ->leftJoin('authenticated_shopper', 'order.shopper_id', '=', 'authenticated_shopper.id')
                        ->where('order.id', '=', $id)
                        ->first(['order.id AS id', 'order.*', 'users.name', 'users.email', 'authenticated_shopper.nif', 'authenticated_shopper.phone_number']);

        return view('pages.order', ['order' => $order]);
    }

    /**
     * Gets all enum values for Order Status.
     *
     * @return Array $records The possible enum status in array.
     */
    public static function getPossibleStatus() {
        $records = DB::select("SELECT unnest(enum_range(NULL::order_state))");

        foreach ($records as $key => $value) {
            $records[$key] = $value->unnest;
        }

        return $records;
    }

    /**
     * Validates form data.
     *
     * @param Array $data Data to be validated
     *
     * @return Array
     */
    private function validateData($data) {
        return Validator::make($data, [
            "id" => 'required|integer|min:1|exists:order,id',
            "status" => [
                'required',
                'string',
                Rule::in(OrderController::getPossibleStatus())
            ],
        ], [], [
            "id" => 'ID',
            "status" => 'status'
        ])->validate();
    }

    /**
     * Updates an order
     *
     * @param Request $request
     * @param int $id ID of the order being edited
     *
     * @return Response
     */
    public function update(Request $request, int $id) {

        $this->authorize('update');

        $data = [
            "id" => $id,
            "status" => $request->input("status")
        ];

        $this->validateData($data);

        $order = Order::find($id);
        $order->update($data);

        return response()->json(
            ["updated-order" => $order],
        200);
    }
}
