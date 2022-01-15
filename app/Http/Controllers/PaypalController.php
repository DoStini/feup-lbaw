<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller {

    public function createTransaction(Request $request) {
        $orderId = $request->route('id');
        $order = Order::findOrFail($orderId);

        $provider = new PayPalClient();
        $provider->setApiCredentials(Config::get('paypal'));
        $data = [
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('finishTransaction', ['id' => $orderId]),
                "cancel_url" => route('orders', ['id' => $orderId])
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $order->total,
                    ]
                ]
            ]
        ];

        $provider->getAccessToken();

        $paypalOrder = $provider->createOrder($data);

        if (isset($paypalOrder['type']) && $paypalOrder['type'] == 'error') {
            return redirect(route('orders', ['id' => $orderId]))->withErrors([
                'message' => $paypalOrder['message']
            ]);
        }

        foreach ($paypalOrder['links'] as $link) {
            if ($link['rel'] == 'approve') {
                $redirect_url = $link['href'];
                break;
            }
        }

        return redirect()->away($redirect_url);
    }

    public function finishTransaction(Request $request) {
        $provider = new PayPalClient();
        $provider->setApiCredentials(Config::get('paypal'));
        $provider->getAccessToken();
        $orderId = $request->route('id');

        $response = $provider->capturePaymentOrder($request->token);

        if (isset($response['type']) && $response['type'] == 'error') {
            $errors = [
                'message' => $response['message'],
            ];
        } else if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $order = Order::findOrFail($orderId);
            $order->status = 'paid';
            $order->save();
        }

        return redirect(route('orders', ['id' => $orderId]));
    }
}
