@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<section id="cart">
    <h1>Shopping Cart of the boy</h1>
    @each('partials.cartproduct', $cart , 'cart_item')
</section>

@endsection