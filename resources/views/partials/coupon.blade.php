<article class="coupon" data-id="{{ $coupon->id }}">
    <h1>{{$coupon->code}}</h1>
    <h3> Percentage </h3>
    <p>{{$coupon->percentage}}</p>
    <h3> Min Value </h3>
    <p>{{$coupon->minimum_cart_value}}</p>
    <h3>Active? </h3>
    <p> {{$coupon->is_active}}</p>
</article>
