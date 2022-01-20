@extends('layouts.error')

@section('content')

<section class="page page-404 vw-100 vh-100">
    <div class="information-text information-text-404">
        <a href={{route('getProductSearch')}} class="ms-md-2 w-100 d-flex justify-content-center">
            <img src="/img/refurniture.svg" alt="" width="350" height="120" />
          </a>
        <h2 class="text-center message-title">Request Submitted</h2>
        <p class="text-center message-error">Thanks for your interest in us. We will be reviewing you questions and reply via email.</p>
        <div class="d-flex w-100 justify-content-evenly">
        </div>
    </div>
</section>
@endsection
