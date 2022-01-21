@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <div class="row">
        <h2 class="text-center">Your purchase is now complete!</h2>
        <p class="text-center">It is now <a class="badge rounded-pill badge-decoration-none badge-{{$order->status}} ">{{strToUpper($order->status)}}</a>.</p>
        <p class="text-center">You can track your order through your purchase history. We'll also notify you whenever an update occurs!</p>
    </div>
    <div class="row">
        <div class="col-md-6 d-flex justify-content-center">
            <a class="my-2 btn btn-primary" href="{{route('getOrders')}}">Purchase History</a>
        </div>
        <div class="col-md-6 d-flex justify-content-center">
            <a class="my-2 btn btn-primary" href="{{route('orders', ['id' => $order->id])}}">See Invoice</a>
        </div>
    </div>
</div>
@endsection