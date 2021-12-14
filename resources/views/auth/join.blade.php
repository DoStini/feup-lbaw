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

                <label for="email-login">Email</label>
                <input id="email-login" type="email" name="email" value="{{ old('email') }}" required autofocus onkeypress="return event.charCode != 32">
                @if ($errors->has('email'))
                    <span class="error">
                    {{ $errors->first('email') }}
                    </span>
                @endif

                <label for="password-login">Password</label>
                <input id="password-login" name="password" type="password" required>
                @if ($errors->has('password'))
                    <span class="error">
                        {{ $errors->first('password') }}
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
            <form id="register-form">
                @csrf

                <label for="name-register">Name</label>
                <input id="name-register" name="name" type="text">

                <label for="name-register">Surname</label>
                <input id="name-register" name="name" type="text">

                <label for="email-register">Email</label>
                <input id="email-register" name="email" type="text" onkeypress="return event.charCode != 32">

                <label for="password-register">Password</label>
                <input id="password-register" name="password" type="password">

                <button value="register">Register</button>
            </form>
        </section>
    </div>
</section>
@endsection
