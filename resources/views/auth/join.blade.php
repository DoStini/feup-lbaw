@extends('layouts.app')

@section('content')
<section id="auth" class="auth in-body">
    <div id="auth-form">
        <section id="login">
            <header>
                <h2>Already have an account?<br>Sign in here</h2>
            </header>
            <form id="oauth-login">
                @csrf

                <button value="oauth-login" type="submit">Sign in with Google</button>
            </form>
            <div>
                or
            </div>
            <form id="login-form" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">@</span>
                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                  </div>

                <div class="row">
                    <div class="col-6">
                        asdfads
                    </div>
                    <div class="col-6">
                        ;===)
                    </div>

                </div>

                <label for="email-login">Email</label>
                <input id="email-login" type="email" name="email" value="{{ old('email') }}" required autofocus onkeypress="return event.charCode != 32">
                @if ($errors->login_form->has('email'))
                    <span class="error">
                    {{ $errors->login_form->first('email') }}
                    </span>
                @endif

                <label for="password-login">Password</label>
                <input id="password-login" name="password" type="password" required>
                @if ($errors->login_form->has('password'))
                    <span class="error">
                        {{ $errors->login_form->first('password') }}
                    </span>
                @endif

                <a href="/">Forgot your password?</a>
                <button value="login" type="submit">Sign In</button>
            </form>

        </section>

        <section id="register">
            <header>
                <h2>Create an account</h2>
            </header>
            <form id="oauth-register">
                @csrf
                <button value="oauth-register" type="submit">Create account with Google</button>
            </form>
            <div>
                or
            </div>
            <form id="register-form"  method="POST" action="{{ route('register') }}">
                @csrf

                <label for="name-register">Name</label>
                <input id="name-register" name="name" type="text">

                {{-- <label for="name-register">Surname</label>
                <input id="name-register" name="surname" type="text"> --}}

                <label for="email-register">Email</label>
                <input id="email-register" type="email" name="email" value="{{ old('email') }}" required autofocus onkeypress="return event.charCode != 32">
                @error('email', 'register_form')
                    <span class="error">
                        {{$message}}
                    </span>
                @enderror

                <label for="password-register">Password</label>
                <input id="password-register" name="password" type="password" required>
                @error('password', 'register_form')
                    <span class="error">
                        {{$message}}
                    </span>
                @enderror

                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required>

                <button value="register">Register</button>
            </form>
        </section>
    </div>
</section>
@endsection
