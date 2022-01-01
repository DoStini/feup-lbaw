@extends('layouts.app')

@section('title', 'Cart')

@section('content')


<section id="cart">
    @if($cart->isEmpty())
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h3>You have no items in your cart. Go get some...</h3>
            <form action="/products">
                <button type="submit" class="btn btn-primary">Go to Product List</button>
            </form>
        </div>
    @else
    <h3 class="mx-2 text-center">{{$user->name}}'s Cart</h3>
    <div class="container-fluid px-5">
        <div class="row">
            <div class="col-8">
                @each('partials.cartproduct', $cart , 'cart_item')
            </div>
            <div class="col-4 d-flex align-items-center justify-content-center flex-column">
                @include('partials.applyCoupon')
                @include('partials.cartTotal', ["cartTotal" => $cartTotal])
                <div class="my-4 w-50 d-flex align-items-center justify-content-center">
                    <a href={{route('checkout-page')}} class="btn btn-primary w-100">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

@endsection
