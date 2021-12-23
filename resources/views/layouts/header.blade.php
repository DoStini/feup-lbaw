<div class="container">
    <div class="row">
        <h1 class="col"><a href="{{ url('/') }}">reFurniture!</a></h1>
        @if (Auth::check())
        <form method="POST" class="w-25 col" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary form-control" href="{{ url('/logout') }}"> Logout </a> <span>{{
                    Auth::user()->name }}</button>
        </form>
        @endif
    </div>
</div>