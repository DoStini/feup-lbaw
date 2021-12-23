@extends('layouts.app')

@section('content')
<script type="text/javascript" src={{ asset('js/login.js') }}></script>

<section id="auth" class="auth container">
    <div id="auth-form" class="row justify-content-md-evenly">
        <section id="login" class="col-md-4 d-flex flex-column">
            <header>
                <h2>Already have an account?<br>Sign in here</h2>
            </header>
            <form id="oauth-login" class="align-self-stretch">
                @csrf

                <button class="btn btn-primary w-100" value="oauth-login" type="submit">Sign in with Google <span class="m-2" ><img src="{{asset('img/arrow_right.svg')}}" alt=""></span></button>
            </form>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="separator"></div>
                <div>or</div>
                <div class="separator"></div>
            </div>
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email-login">Email</label>
                    <input id="email-login" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus onkeypress="return event.charCode != 32">
                    @if ($errors->login_form->has('email'))
                        <span class="error form-text">
                        {{ $errors->login_form->first('email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-login">Password</label>

                    <div class="input-group">
                        <input id="password-login" class="form-control" name="password" type="password" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" value="password-login" onclick="togglePassword(this);"><img src="{{asset('img/eye.svg')}}" alt=""></button>
                        </div>
                    </div>
                    @if ($errors->login_form->has('password'))
                        <span class="error form-text">
                            {{ $errors->login_form->first('password') }}
                        </span>
                    @endif
                </div>

                <div class="d-flex flex-lg-row flex-column justify-content-end mt-3">
                    {{-- <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="defaultCheck2">
                            Remember Me
                        </label>
                    </div> --}}
                    <a href="/">Forgot your password?</a>
                </div>

                <button value="login" class="btn btn-primary w-100 mt-3" type="submit">Sign In <span class="m-2" ><img src="{{asset('img/arrow_right.svg')}}" alt=""></span></button>
            </form>

        </section>
        <section id="register" class="col-md-4 d-flex flex-column">
            <header>
                <h2>Create an account</h2>
            </header>
            <form id="oauth-register" class="align-self-stretch">
                @csrf
                <button type="submit" value="oauth-register" class="btn btn-primary w-100" type="submit">Create account with Google <span class="m-2" ><img src="{{asset('img/arrow_right.svg')}}" alt=""></span></button>
            </form>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="separator"></div>
                <div>or</div>
                <div class="separator"></div>
            </div>
            <form id="register-form"  method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="name-register">Name</label>
                    <input class="form-control" id="name-register" name="name" type="text">
                </div>

                {{-- <label for="name-register">Surname</label>
                <input id="name-register" name="surname" type="text"> --}}

                <div class="form-group">
                    <label for="email-register">Email</label>
                    <input class="form-control" id="email-register" type="email" name="email" value="{{ old('email') }}" required autofocus onkeypress="return event.charCode != 32">
                    @error('email', 'register_form')
                        <span class="error form-text">
                            {{$message}}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-register">Password</label>

                    <div class="input-group">
                        <input class="form-control" id="password-register" name="password" type="password" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" value="password-register" onclick="togglePassword(this);"><img src="{{asset('img/eye.svg')}}" alt=""></button>
                        </div>
                    </div>
                    @error('password', 'register_form')
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
                            <button type="button" class="btn btn-outline-secondary" value="password-confirm" onclick="togglePassword(this);"><img src="{{asset('img/eye.svg')}}" alt=""></button>
                        </div>
                    </div>
                </div>

                <button type="submit" value="register" class="w-100 mt-3 btn btn-primary">Register<span class="m-2" ><img src="{{asset('img/arrow_right.svg')}}" alt=""></span></button>
            </form>
        </section>
    </div>
</section>
@endsection
