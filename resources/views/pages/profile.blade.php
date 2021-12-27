@extends('layouts.app')

@section('title', $shopper->user->name)

@section('content')
@include('partials.profile', ['shopper' => $shopper, 'page' => $page])
@endsection
