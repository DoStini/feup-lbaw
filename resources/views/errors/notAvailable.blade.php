@extends('layouts.error')

@section('content')

<section class="page page-403 vw-100 vh-100">
    <div class="information-text information-text-403">
        <a href={{route('getProductSearch')}} class="ms-md-2 w-100 d-flex justify-content-center">
            <img src="/img/refurniture.svg" alt="reFurniture Logo" width="350" height="120" />
          </a>
        <h2 class="text-center message-title">Not Available</h2>
        <p class="text-center message-error">This page is no longer available.</p>
    </div>
</section>
@endsection