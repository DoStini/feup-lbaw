@extends('layouts.app')

@section('title', $product->name)

@section('content')
<script type="text/javascript">const productInfo = <?= $product ?>;</script>
<script type="text/javascript" src={{ asset('js/product.js') }} defer></script>
@include('partials.product', ['product' => $product])
@endsection