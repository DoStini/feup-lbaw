{{--@foreach ($shopper->orders as $order)
    <h3>{{$order}}</h3>
    <p>{{ $order->payment }}</p>
    <p>{{ $order->address }}</p>
    <p>{{ $order->coupon }}</p>
    @foreach ($order->products as  $product)
        <p>{{$product}}</p>
    @endforeach
@endforeach--}}

<div>
    @foreach ($shopper->orders as $order)
        <div class="accordion" id={{ "order" . $loop->iteration}}>
            <div class="accordion-item my-4">
            <h2 class="accordion-header" id={{"panelsStayOpen-heading" . $loop->iteration}}>
                <div class="container p-0">
                    <div class="row m-0 p-2 bg-secondary text-white">
                        <div class="col-6">
                            <h5 class="text-start">Order ID: {{$order->id}}</h5>
                        </div>
                        <div class="col-6">
                            <h5 class="text-end">{{date("d M Y, H:i", strtotime($order->timestamp))}}</h5>
                        </div>
                    </div>
                    <div class="row my-2 p-4">
                        <div class="col-5">
                            <h6>Total</h6>
                            <h6>Payment Method</h6>
                            <h6>Current Status</h6>
                        </div>
                        {{-- If needed to indicate with color scheme the order status
                        @php
                            $color = null;
                            if($order->status == 'created') $color = 'bg-primary';
                            elseif($order->status == 'paid') $color = 'bg-info';
                            elseif($order->status == 'processing') $color = 'bg-warning';
                            elseif($order->status == 'shipped') $color = 'bg-success';
                            else $color = 'bg-warning';
                        @endphp
                        
                        --}}
                        <div class="col-4">
                            <h6>{{$order->total}}</h6>
                            <h6>{{$order->payment->paypal_transaction_id == null ? 'Reference' : 'PayPal'}}</h6>
                            <h6>{{strToUpper($order->status)}}</h6>
                        </div>
                        <div class="col-3">
                            <button class="btn btn-primary w-100 collapsed" type="button" data-bs-toggle="collapse" data-bs-target={{"#panelsStayOpen-collapse" . $loop->iteration}} aria-expanded="true" aria-controls={{"panelsStayOpen-collapse" . $loop->iteration}}>
                                View More Details
                            </button>
                        </div>
                    </div>
                </div>
            </h2>
            <div id={{"panelsStayOpen-collapse" . $loop->iteration}} class="accordion-collapse collapse" aria-labelledby={{"panelsStayOpen-heading" . $loop->iteration}}>
                <div class="accordion-body">
                <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                </div>
            </div>
            </div>
        </div>
    @endforeach
</div>


