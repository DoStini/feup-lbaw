@extends('layouts.app')

@section('title', $shopper->name)

@section('content')
@include('partials.shopper', ['shopper' => $shopper])
@endsection