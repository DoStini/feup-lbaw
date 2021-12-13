@extends('layouts.app')

@section('title', $product->name)

@section('content')
@include('partials.product', ['product' => $product])
@endsection