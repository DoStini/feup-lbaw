@extends('layouts.logoOnlyApp')

@section('content')

<section class="container">
    <h2>Invalid Link</h2>
    <p>The password recover link has expired or is invalid</p>
    <p>
        <a href="{{route('join')}}">Login</a>
    </p>
    <p>
        <a href="{{route('recoverPage')}}">Recover</a>
    </p>
</section>
@endsection
