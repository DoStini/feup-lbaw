<?php

namespace App\Http\Controllers;

use App\Models\Order;

class OrderController extends Controller {

    public function show($id) {
        $order = Order::find($id);
        return view('pages.order', ['order' => $order]);
    }
}