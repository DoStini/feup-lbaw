<!--<div class="container">
    <div class="row">
        <a href="{{ url('/') }}"><img src="/img/refurniture.svg"></a>
        @if (Auth::check())
        <form method="POST" class="w-25 col" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-primary form-control" href="{{ url('/logout') }}"> Logout </a> <span>{{
                    Auth::user()->name }}</button>
        </form>
        @endif
    </div>
</div>-->

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid d-flex justify-content-between">
      <a class="navbar-brand" href="#">
        <img src="/img/refurniture.svg" alt="" width="300" height=100">
      </a>

      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="What are you looking for?" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>

      <div>
          @if(Auth::check())
            <i class="bi bi-bell"></i>
            <i class="bi bi-person"></i>
            <button type="submit" class="btn btn-outline-primary form-control" href="{{ url('/logout') }}"> Logout </button>
          @else
            <button class="btn btn-outline-primary">Sign-In</button>
          @endif
      </div>

    <div>
        <i class="bi bi-cart"></i>
    </div>

    </div>
  </nav>