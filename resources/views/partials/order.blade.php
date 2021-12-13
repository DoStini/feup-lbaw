<article class="order" data-id="{{ $order->id }}">
    <h1>{{$order->timestamp}}</h1>
    <h3> My funny total </h3>
    <p>{{$order->total}}</p>
    <h3> My funny subtotal </h3>
    <p>{{$order->subtotal}}</p>
    <h3> My funny status </h3>
    <p>{{$order->status}}</p>
    <h3> My funny FUNNY products </h3>
    @each('partials.orderproduct', $order->products, 'order_item')
    <h3> My funny FUNNY address </h3>

    @include('partials.address', ['address' => $order->address])

    @isset($order->coupon)
        @include('partials.coupon', ['coupon' => $order->coupon])
    @endisset

    @isset($order->payment)
        @include('partials.payment', ['payment' => $order->payment])
    @endisset

</article>
