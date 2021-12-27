@extends('layouts.app')

@section('title', 'Cart')

@section('content')


<section id="cart">
    @if($cart->empty())
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
                <div class="my-4 w-75">
                    <h4>Apply Coupon </h4>
                    <div class="d-flex my-3 align-items-center">
                        <input class="w-50" type="text">
                        <button type="button" class="btn btn-primary mx-3 p-1 w-25">Apply</button>
                    </div>
                </div>
                <div class="my-4 container">
                    <div class="row mx-3">
                        <h4>Order Summary</h4>
                    </div>
                    <br>
                    <div class="row">
                        <table id="vertical-1" style="border-spacing: 2em .5em; border-collapse: inherit;">
                            <tr>
                                <th>Subtotal (Tax Excluded):</th>
                                <td>124.99 €</td>
                            </tr>
                            <tr>
                                <th>Total Tax (IVA):</th>
                                <td>26.25 €</td>
                            </tr>
                            <tr style="font-size: 1.3em;">
                                <th>Total (Tax Included):</th>
                                <td>151.24 €</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="my-4 w-50 d-flex align-items-center justify-content-center">
                    <button type="button" class="btn btn-primary w-100">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

@endsection