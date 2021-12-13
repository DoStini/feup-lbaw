<article class="cart_item" data-id="{{ $cart_item->id }}">
    <h1>{{$cart_item->name}}</h1>
    <h3> Amount </h3>
    <p>{{$cart_item->details->amount}}</p>
    <h3> Desc </h3>
    <p>{{$cart_item->description}}</p>
    <h3>Price</h3>
    <p> {{$cart_item->price}} £</p>
    <h3>Rate</h3>
    <p> {{$cart_item->avg_stars}}</p>
</article>
