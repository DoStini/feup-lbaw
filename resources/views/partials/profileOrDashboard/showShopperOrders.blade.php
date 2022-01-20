<style>
    .product-link{
        text-decoration: none !important;
    }

    .product-link:hover {
        color: white;
    }
</style>

<div>
    @if($shopper->orders->isEmpty())
        <div class="d-flex justify-content-center align-items-center">
            <h4>You haven't made any purchases yet.</h4>
        </div>
    @else
        @foreach ($shopper->orders()->orderByDesc('timestamp')->get() as $order)
            <div class="accordion" id={{ "order" . $loop->iteration}}>
                <div class="accordion-item my-4">
                <h2 class="accordion-header" id={{"panelsStayOpen-heading" . $loop->iteration}}>
                    <div class="container p-0">
                        <div class="row m-0 p-2 bg-primary text-white">
                            <div class="col-6">
                                <h5 class="text-start">Order ID: {{$order->id}}</h5>
                            </div>
                            <div class="col-6">
                                <h5 class="text-end">{{date("d M Y, H:i", strtotime($order->timestamp))}}</h5>
                            </div>
                        </div>
                        <div class="row my-2 p-4">
                            <div class="col-md-9">
                                <table class="table table-borderless">
                                    <tbody style="font-size: 0.5em;">
                                        <tr>
                                            <th scope="row">Total</th>
                                            <td style="font-family: 'Alata', sans-serif;">{{$order->total}} €</td>
                                        </tr>
                                        @if($order->payment)
                                        <tr>
                                            <th scope="row">Payment Method</th>
                                            <td style="font-family: 'Alata', sans-serif;">{{$order->payment->entity != null ? 'Bank Transfer' : 'PayPal'}}</td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Current Status</th>
                                            <td style="font-family: 'Alata', sans-serif;"><h6><a id="badge-{{$order->id}}" class="badge rounded-pill badge-decoration-none badge-{{$order->status}} ">{{strToUpper($order->status)}}</a></h6></td>
                                        </tr>
                                    <tbody>
                                </table>
                            </div>
                            <div class="col-md-3 d-flex flex-column align-items-center justify-content-center">
                                <a class="btn btn-outline-secondary w-100 collapsed m-1" href={{route('orders', ['id' => $order->id])}}>Invoice</a>
                                <button class="btn btn-outline-secondary w-100 collapsed" type="button" data-bs-toggle="collapse" data-bs-target={{"#panelsStayOpen-collapse" . $loop->iteration}} aria-expanded="true" aria-controls={{"panelsStayOpen-collapse" . $loop->iteration}}>
                                    View More Details
                                </button>
                                @if ($order->status == "created")
                                    <button id="cancel-order-btn" class="btn btn-outline-danger w-100 collapsed m-1" onclick="cancelOrder({{$order->id}})">
                                        Cancel order
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </h2>
                <div id={{"panelsStayOpen-collapse" . $loop->iteration}} class="accordion-collapse collapse" aria-labelledby={{"panelsStayOpen-heading" . $loop->iteration}}>
                    <div class="accordion-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="container m-0 p-0">
                                        <div class="row">
                                            <div class="col-12">
                                                <h5><span class="badge bg-secondary">Shipment Details</span></h5>
                                                <p class="p-2 m-0">{{$order->address->street}}, nº{{$order->address->door}}</p>
                                                <p class="p-2 m-0">{{$order->address->zip_code->zip_code}}, {{$order->address->zip_code->county->name}}</p>
                                                <p class="p-2 m-0">{{$order->address->zip_code->county->district->name}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <h5><span class="badge bg-secondary">Order Summary</span></h5>
                                    <div class="table-responsive">
                                        <table class="table d-inline-flex table-borderless">
                                            <tbody>
                                                <tr>
                                                <th scope="row" style="width: 15em;">Subtotal</th>
                                                <td>{{$order->subtotal}} €</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row" style="width: 15em;">Used Coupon?</th>
                                                    <td>{{$order->coupon ? 'Yes' : 'No'}}</td>
                                                </tr>
                                                @if($order->coupon)
                                                    <tr>
                                                        <th scope="row" style="width: 15em;">Code</th>
                                                        <td>{{$order->coupon->code}} ({{$order->coupon->is_active ? 'still active' : 'no longer active'}})</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" style="width: 15em;">Percentage Discount</th>
                                                        <td>{{round($order->coupon->percentage * 100 )}}%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" style="width: 15em;">Total Discount</th>
                                                        <td>{{$order->subtotal - $order->total}} €</td>
                                                    </tr>
                                                @endif
                                            <tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5 container my-2">
                                    <h5><span class="badge bg-secondary">Payment</span></h5>
                                    @if($order->payment)
                                        <div class="table-responsive">
                                            <table class="table d-inline-flex table-borderless">
                                                <tbody>
                                                    @if(!$order->payment->entity)
                                                        @if($order->status !== 'created')
                                                            <tr>
                                                            <th scope="row">PayPal Transaction ID</th>
                                                            <td>{{$order->payment->paypal_transaction_id}}</td>
                                                            </tr>
                                                        @else
                                                            <form method="GET" action="{{route('createTransaction', ['id' => $order->id])}}">
                                                            <button type="submit" class="text-end btn btn-primary" >Pay using paypal</button>
                                                            </form>
                                                        @endif
                                                    @else
                                                        <tr>
                                                            <th scope="row">Reference</th>
                                                            <td>{{$order->payment->reference}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">Entity</th>
                                                            <td>{{$order->payment->entity}}</td>
                                                        </tr>
                                                    @endif
                                                <tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p>The payment method is yet to be defined.</p>
                                    @endif
                                </div>
                                <div class="col-md-7 my-2">
                                    <h5><span class="badge bg-secondary">Bought Products</span></h5>
                                    <div class="table-responsive">

                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col" style="width: 15em;">Product Name</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Unit Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->products as $product)
                                                <tr>
                                                    <td><a class="badge rounded-pill bg-primary product-link"
                                                        href={{route('getProduct', ['id' => $product->id])}} target="_blank"
                                                        style="width: 15em; overflow: hidden;   text-overflow: ellipsis;">
                                                        {{$product->name}}</a></td>
                                                    <td>{{$product->details->amount}}</td>
                                                    <td>{{$product->details->unit_price}} €</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="modal fade" id="confirm-cancel" tabindex="-1" aria-labelledby="confirmTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="confirmContent">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTitle">Confirm delete account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body" id="confirmBody">
                This is an irreversible action, are you sure you want to continue?
                <button id="confirm-cancel" class="my-2 btn btn-danger w-100 cancel-order-btn">
                    Cancel Order
                </button>
            </div>
        </div>
    </div>
</div>

<script defer>
        function cancelOrder(orderId) {
            const confirmElem =  document.getElementById("confirm-cancel");
            let confirmCancel = new bootstrap.Modal(confirmElem);
            console.log(confirmElem)
            confirmElem.querySelector('.cancel-order-btn').addEventListener("click", () => {
            jsonBodyPost(`/api/orders/${orderId}/cancel`)
            .then(() => {
                    const elem = document.getElementById(`badge-${orderId}`);
                    elem.classList.add("badge-canceled");
                    elem.innerText = "CANCELED";
                    document.getElementById("cancel-order-btn").remove();
                    confirmCancel.hide();

                })
                .catch();
        });
        confirmCancel.show();
    }

</script>
