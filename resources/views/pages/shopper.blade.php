@extends('layouts.app')

@section('title', $shopper->name)

@section('content')
@include('partials.shopper', ['shopper' => $shopper])

<form method="POST" action="/api/users/private/{{Auth::id()}}/edit">
    <label for="name"> Name</label>
    <input id="name" type="text" name="name" required>

    <button type="submit"></button>
</form>

@endsection
