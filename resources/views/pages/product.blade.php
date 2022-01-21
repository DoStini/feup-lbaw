@extends('layouts.app')

@section('title', $product->name)

@section('content')
<script>const productInfo = <?= $product ?>;</script>
<script src={{ asset('js/product.js') }} defer></script>
@include('partials.product', ['product' => $product, 'wishlisted' => $wishlisted])
@endsection
