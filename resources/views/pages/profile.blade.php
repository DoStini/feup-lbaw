@extends('layouts.app')

@if(!$shopper)
    @section('title', $admin->name)
@else
    @section('title', $shopper->user->name)
@endif

@section('content')
@include('partials.profileOrDashboard', ['shopper' => $shopper ?? null, 'admin' => $admin ?? null, 'page' => $page, 'links' => 'profileLinks'])
@endsection
