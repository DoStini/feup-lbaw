@extends('layouts.logoOnlyApp')

@section('content')

@include('partials.errormodal')

<script type="text/javascript" src={{ asset('js/login.js') }}></script>

<section id="auth" class="auth container">
    <div class="row justify-content-center">
        <div id="register" class="col-lg-6">
            <h2>Set New Password</h2>
            <form id="recover-form" method="POST" action="{{route('newPassword')}}">
                @csrf

                <input id="token" name="token" style="display:none" value="{{$token}}">
                </input>
                <div class="form-group">
                    <label for="password-register">Password</label>

                    <div class="input-group">
                        <input class="form-control" id="password-register" name="password" type="password" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary" value="password-register" onclick="togglePassword(this);"><img src="{{asset('img/eye.svg')}}" alt="Toggle Password"></button>
                        </div>
                    </div>
                    @error('password', 'new_password_form')
                        <span class="error form-text">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>

                    <div class="input-group">
                    <input class="form-control" id="password-confirm" type="password" name="password_confirmation" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-primary" value="password-confirm" onclick="togglePassword(this);"><img src="{{asset('img/eye.svg')}}" alt="Toggle Password"></button>
                        </div>
                    </div>
                </div>

                <button type="submit" value="register" class="w-100 mt-3 btn btn-primary">Recover<span class="m-2" ><img src="{{asset('img/arrow_right.svg')}}" alt="Register Arrow"></span></button>
            </form>
        </div>
    </div>
</section>
@endsection
