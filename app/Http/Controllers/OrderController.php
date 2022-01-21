<?php

namespace App\Http\Controllers;

use App\Events\OrderUpdate;
use App\Models\Order;
use App\Exceptions\ApiError;
use App\Models\Product;
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

    public function list(Request $request) {
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
            "status" => [
                'nullable',
                'string',
                Rule::in(OrderController::getPossibleStatus())
            ],
        ], [], [
            "id" => 'ID',
            "status" => 'status'
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
            "shipped" => "shipped",
            "canceled" => "canceled"
        ];
        return $next[$status];
    }

    /**
     * Updates an order
     *
     * @param Request $request includes optional parameter "status" to update to
     * @param int $id ID of the order being edited
     *
     * @return Response
     */
    public function update(Request $request, int $id) {
        $this->authorize('updateAny', Order::class);

        $data = [
            "id" => $id,
            "status" => $request->input("status")
        ];

        $this->validateData($data);

        $order = Order::find($id);

        if ($data["status"] == null) {
            $old_status = $order->status;

            if ($old_status == "shipped")
                return ApiError::orderAtTerminalState();
        
            if ($old_status == "canceled")
                return ApiError::orderCanceled();

            $data["status"] = $this->getNextStatus($old_status);
        }

        $order->update($data);

        event(new OrderUpdate($order->shopper->id, $order->id, $data["status"]));
        return response()->json(
            ["updated-order" => $order],
            200
        );
    }

    /**
     * Cancels an order
     * 
     * @param Request $request includes order id of the order to be canceled
     * 
     * @return Response
     */
    public function cancel(Request $request) {
        $order = Order::findOrFail($request->route('id'));
        $this->authorize('cancel', [Order::class, $order]);

        try {
            DB::beginTransaction();
            DB::unprepared("SET TRANSACTION ISOLATION LEVEL SERIALIZABLE;");

            $items = $order->products;

            foreach ($items as $item) {
                $product = Product::find($item->id);
                $product->update([
                    "stock" => $product->stock + $item->details->amount
                ]);
            }

            $order->update([
                "status" => "canceled"
            ]);
            DB::commit();
        } catch (QueryException $ex) {
            DB::rollBack();

            return redirect()->back()->withErrors(["order" => "Unexpected Error"])->withInput();
        }

        event(new OrderUpdate($order->shopper->id, $order->id, "canceled"));
        return response()->json(
            ["updated-order" => $order],
            200
        );
    }
}
