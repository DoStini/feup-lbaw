<article class="shopper" data-id="{{ $shopper->id }}">
    <h1>{{$shopper->user->name}}</h1>
    <h3> My funny number </h3>
    <p>{{$shopper->phone_number}}</p>
    <h3> My funny email </h3>
    <p>{{$shopper->user->email}}</p>
    <h3>About me</h3>
    <p> {{$shopper->about_me}}</p>
    <h3>Addresses</h3>
    @each('partials.address', $shopper->addresses, 'address')
    <h3>Orders</h3>
    @each('partials.order', $shopper->orders, 'order')
</article>
