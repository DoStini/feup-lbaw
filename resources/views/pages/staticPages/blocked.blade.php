@extends('layouts.logoOnlyApp')

@section('content')

<section class="container">
    <div class="row">
        <h2 class="text-center">Your account has been banned.</h2>
    </div>
    <div class="row">
        <p class="text-center">If you think this was a mistake, please contact us through one of our contact mediums.</p>
    </div>
    <div class="row d-flex justify-content-center">
        <a class="btn btn-primary" style="width: fit-content;" href={{route('contact-us')}}>Contact Us</a>
    </div>
</section>
@endsection