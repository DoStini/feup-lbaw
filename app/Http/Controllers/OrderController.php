<?php

namespace App\Http\Controllers;

use App\Events\OrderUpdate;
use App\Models\Order;
use App\Exceptions\ApiError;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller {

    public function show($id) {
        $order = Order::findOrFail($id);

        $this->authorize('view', [Order::class, $order]);

        $order = Order::leftJoin('users', 'order.shopper_id', '=', 'users.id')
            ->leftJoin('authenticated_shopper', 'order.shopper_id', '=', 'authenticated_shopper.id')
            ->where('order.id', '=', $id)
            ->first(['order.id AS id', 'order.*', 'users.name', 'users.email', 'authenticated_shopper.nif', 'authenticated_shopper.phone_number']);

        return view('pages.order', ['order' => $order]);
    }

    public function list(Request $request)  {
        $this->authorize('viewAny', Order::class);

        $dc =  new DatatableController();
        return $dc->get($request, DB::table('order_shopper'));
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
        ], [], [
            "id" => 'ID'
        ])->validate();
    }

    /**
     * Returns next order status given current one.
     * 
     * @param Status $status Previous status
     * 
     * @return Status Next status
     */
    public static function getNextStatus($status) {
        static $next = [
            "created" => "paid",
            "paid" => "processing",
            "processing" => "shipped",
        ];
        return $next[$status];
    }

    /**
     * Updates an order
     *
     * @param int $id ID of the order being edited
     *
     * @return Response
     */
    public function update(int $id) {
        $this->authorize('updateNext', Order::class);

        $data = [
            "id" => $id
        ];

        $this->validateData($data);

        $order = Order::find($id);
        $old_status = $order->status;

        if($old_status == "shipped")
            return ApiError::orderAtTerminalState();
        
        $data["status"] = $this->getNextStatus($old_status);
        $order->update($data);

        event(new OrderUpdate($order->shopper->id, $order->id, $data['status']));
        return response()->json(
            ["updated-order" => $order],
            200
        );
    }
}
