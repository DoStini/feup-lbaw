<article class="order_item" data-id="{{ $order_item->id }}">
    <h5>{{$order_item->name}}</h1>
    <h6> Amount </h3>
    <p>{{$order_item->details->amount}}</p>
    <h6>Price</h3>
    <p> {{$order_item->details->unit_price}} £</p>
</article>
