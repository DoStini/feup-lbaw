@extends('layouts.app')

@section('title', 'API TESTING')

@section('content')

<form class="container form" method="POST" action="{{route('addProduct')}}">
    @csrf


    <button type="submit"> SUBMIT</button>
</form>

@endsection
