@extends('layouts.logoOnlyApp')

@section('content')
<form method="POST" action="{{route('contact-us-submit')}}">
@csrf
<div class="container">
    <div class="row">
        <h1 class="text-center">Got any question? Contact us!</h1>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label for="name">Name</label>
            <input required id="name" class="form-control" type="text" name="name" value="">
            <span class="error form-text text-danger" id="name-error"></span>
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email to Answer</label>
            <input required id="email" class="form-control" type="email" name="email" value="">
            <span class="error form-text text-danger" id="email-error"></span>
        </div>
        <div class="mb-3">
            <label for="contact-us-message"> Message</label>
            <textarea required id="contact-us-message" class="form-control" name="message" value=""></textarea>
            <span class="error form-text text-danger" id="about_me-error"></span>
        </div>
    </div>
    <div class="row justify-content-center">
        <button type="submit" style="width: 10em" class="btn btn-primary">Send!</button>
    </div>
</div>
</form>
@endsection
