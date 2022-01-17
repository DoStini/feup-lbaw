<h1>Recover Account</h1>

<p>
    Hello, {{$user->name}}.
</p>

<p>We have received a request to update your account credentials. If you weren't the one requesting this, someone might be trying to access your account.</p>

<p>If you are trying to recover your account, please access <a href="{{$link}}"> this link</a> to finish the process.</p>

@include('email.footer')
