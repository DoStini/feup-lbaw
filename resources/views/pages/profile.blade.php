@extends('layouts.app')

@if(!$shopper)
    @section('title', $admin->name)
@else
    @section('title', $shopper->user->name)
@endif

@section('content')
@include('partials.profile', ['shopper' => $shopper, 'admin' => $admin, 'page' => $page])
@endsection
