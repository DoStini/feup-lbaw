<div class="d-flex justify-content-center">
    <div class="container w-75 bg-greyish">
        <div class="row m-5">
            <div class="col-6 d-flex justify-content-center justify-content-md-start my-3">
                <a href={{route('getProductSearch')}} class="ms-md-2">
                  <img src="/img/refurniture.svg" alt="" width="200" height=65" />
                </a>
              </div>
            <div class="col-6">
                <h3 class="text-end">Invoice - {{$order->id}}</h3>
                <p class="text-end">Status!!!</p>
                <h5 class="text-end">{{date("d M Y", strtotime($order->timestamp))}}</h5>
            </div>
        </div>
        <div class="row m-5">
            <div class="col-4">
                <h5>From: reFurniture</h5>
                <p>105, 2ET Rua Jaime Leão Pinto</p>
                <p>4590-831, Paços de Ferreira</p>
                <p>Porto</p>
                <p>Phone: +351 912345678</p>
                <p>NIF: 262513301</p>
                <p>Email: support@refurniture.com</p>
            </div>
            <div class="col-4">
                <h5>To: {{$order->name}}</h5>
                <p>{{$order->address->door}} {{$order->address->street}}</p>
                <p>{{$order->address->zip_code->zip_code}}, {{$order->address->zip_code->county->name}}</p>
                <p>{{$order->address->zip_code->county->district->name}}</p>
                <p>Phone: +351 {{$order->phone_number}}</p>
                <p>NIF: {{$order->nif}}</p>
                <p>Email: {{$order->email}}</p>
            </div>
            <div class="col-4 d-flex justify-content-end">
                <div>
                    <h5 class="text-end">Payment Details</h5>
                    <p class="text-end">Total Amount: {{round($order->total, 2)}}€</p>
                    <p class="text-end">Date: {{date("d/m/Y", strtotime($order->timestamp))}}</p>
                    @if($order->payment)
                            <p class="text-end">Payment Method: {{$order->payment->paypal_transaction_id == null ? 'Bank Transfer' : 'PayPal'}} </p>
                            @if($order->payment->paypal_transaction_id)
                                <p class="text-end">Transaction ID: {{$order->payment->paypal_transaction_id}}</p>
                            @else
                                <p class="text-end">Reference: {{$order->payment->reference}}</p>
                                <p class="text-end">Entity: {{$order->payment->entity}}</p>
                            @endif
                    @endif
                </div>
            </div>
        </div>
        <div class="row m-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Total Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->products as $product)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td><a class="text-decoration-none" 
                                href={{route('getProduct', ['id' => $product->id])}} target="_blank">
                                {{$product->name}}</a></td>
                            <td>{{$product->details->amount}}</td>
                            <td>{{number_format($product->details->unit_price * (1-0.23),2)}} €</td>
                            <td>{{number_format($product->details->unit_price * $product->details->amount * (1-0.23), 2)}} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row m-5">
            <div class="col-6">

            </div>
            <div class="col-6 d-flex justify-content-end flex-column">
                <p class="text-end">Subtotal (Without Tax): {{number_format($order->subtotal * (1-0.23), 2)}} €</p>
                <p class="text-end">Tax (23%): {{number_format($order->subtotal * 0.23, 2)}} €</p>
                @if($order->coupon)
                <p class="text-end">Coupon: {{$order->coupon->code}} ({{round($order->coupon->percentage * 100 )}}%)</p>
                <p class="text-end">Discount: {{number_format($order->subtotal - $order->total, 2)}} €</p>
                @endif
                <p class="text-end">Total Amount: {{number_format($order->total, 2)}} €</p>
            </div>
        </div>
    </div>
</div>