@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

<form class="container form" method="POST" action="{{route('checkout')}}">
    @csrf
    <label for="address-id"> ADDRESS ID</label>
    <input type="number" name="address-id">
    <label for="coupon-id"> COUPON ID</label>
    <input type="number" name="coupon-id">
    <label for="payment-type"> PAYMENT TYPE</label>
    <input type="string" name="payment-type">

    <button type="submit"> SUBMIT</button>
</form>

@endsection
