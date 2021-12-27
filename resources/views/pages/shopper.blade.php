@extends('layouts.app')

@section('title', $shopper->user->name)

@section('content')
@include('partials.shopper', ['shopper' => $shopper])
@endsection