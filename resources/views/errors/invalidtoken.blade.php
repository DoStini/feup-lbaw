@extends('layouts.error')

@section('content')

<section class="page page-404 vw-100 vh-100">
    <div class="information-text information-text-404">
        <a href={{route('getProductSearch')}} class="ms-md-2 w-100 d-flex justify-content-center">
            <img src="/img/refurniture.svg" alt="reFurniture Logo" width="350" height="120" />
          </a>
        <h2 class="text-center message-title">Invalid Link</h2>
        <p class="text-center message-error">The password recovery link has expired or is invalid.</p>
        <div class="d-flex w-100 justify-content-evenly">
            <a class="btn btn-primary w-25" href="{{route('join')}}">Login</a>
            <a class="btn btn-primary w-25" href="{{route('recoverPage')}}">Recover</a>
        </div>
    </div>
</section>
@endsection
