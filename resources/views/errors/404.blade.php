@extends('layouts.error')

@section('content')

<section class="page page-404 vw-100 vh-100">
    <div class="information-text information-text-404">
        <a href={{route('getProductSearch')}} class="ms-md-2 w-100 d-flex justify-content-center">
            <img src="/img/refurniture.svg" alt="" width="350" height="120" />
          </a>
        <h2 class="text-center message-title">404 - Not Found</h2>
        <p class="text-center message-error">The page you are trying to access does not exist.</p>
    </div>
</section>
@endsection
