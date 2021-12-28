@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

@if($errors->any())
@foreach($errors->getMessages() as $key => $message)
    <p>{{$key}} = @foreach ($message as $mess) {{$mess}}</p><br> @endforeach
@endforeach
@endif

<form class="container form" method="POST" action="{{route('checkout')}}">
    @csrf
    <label for="address-id"> ADDRESS ID</label>
    <input type="number" name="address-id" value="{{old('address-id')}}">
    <label for="coupon-id"> COUPON ID</label>
    <input type="number" name="coupon-id" value="{{old('coupon-id')}}">
    <label for="payment-type"> PAYMENT TYPE</label>
    <input type="string" name="payment-type" value="{{old('payment-type')}}">

    <button type="submit"> SUBMIT</button>
</form>

@endsection
