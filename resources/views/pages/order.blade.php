@extends('layouts.app')

@section('title', $order->id)

@section('content')
@include('partials.order', ['order' => $order])
@endsection