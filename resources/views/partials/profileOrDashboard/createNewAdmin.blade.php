<script type="text/javascript" src={{ asset('js/login.js') }}></script>

<form id="admin-register-form"  method="POST" action={{route('registerAdmin')}}>
    @csrf

    <div class="form-group">
        <label for="name-register">Name</label>
        <input class="form-control" id="name-register" name="name" type="text">
    </div>

    <div class="form-group">
        <label for="email-register">Email</label>
        <input class="form-control" id="email-register" type="email" name="email" value="{{ old('email') }}" required autofocus onkeypress="return event.charCode != 32">
        @error('email', 'admin_register_form')
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
        @error('password', 'admin_register_form')
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

    <button type="submit" class="w-100 mt-3 btn btn-primary">Create Admin</button>
</form>