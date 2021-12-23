@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<section id="cart">
    <h3 class="mx-2">{{$user->name}}'s Cart</h3>
    <div class="d-flex">
        <div class="w-50 mx-4">
            @each('partials.cartproduct', $cart , 'cart_item')
        </div>
        <div class="mx-4">
            <div class="my-4">
                <h4>Apply Coupon </h4>
                <div class="d-flex">
                    <input type="text">
                    <button type="button" class="btn btn-primary mx-4">Apply Coupon</button>
                </div>
            </div>
            <div class="my-4">
                <h4>Order Summary</h4>
                <br>
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
            <div class="my-4">
                <button type="button" class="btn btn-primary">Proceed to Purchase Process</button>
            </div>
        </div>
    </div>
</section>

@endsection