<h1>Blocked Account</h1>

<p>
    Hello, {{$user->name}}.
</p>

<p>After internal analysis, we have decided to block your account for not following the store rules.</p>
<p>If you believe this was a mistake, contact us at <a href="{{route("contacts")}}"> </p>

@include('email.footer')
