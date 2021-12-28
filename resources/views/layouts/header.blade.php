<!-- Navbar -->
<nav>
  <div class="p-3 text-center text-white">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-md-4 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0">
          <a href="#" class="ms-md-2">
            <img src="/img/refurniture.svg" alt="" width="200" height=65" />
          </a>
        </div>

        <div class="col-md-4">
          <form class="d-flex input-group w-auto my-auto mb-3 mb-md-0">
            <input autocomplete="off" type="search" class="form-control rounded" placeholder="Search" />
            <span class="input-group-text border-0 d-none d-lg-flex"><i class="fas fa-search"></i></span>
          </form>
        </div>

        <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
          <div class="d-flex align-items-center">
            @if(Auth::check())
                @if(!Auth::user()->is_admin)
                <!-- Cart -->
                <a id="cart-dropdown" class="me-5 text-reset hidden-arrow dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"" href="#">
                    <i class="fas fa-shopping-cart" style="color: #000000; font-size:1.5em;"></i>
                </a>
                <ul id="cart-dropdown-menu" class="dropdown-menu dropdown-menu-end" aria-labelledby="cart-dropdown">
                    <li>
                        <div class="dropdown-item container" href="#">
                            <div class="row align-items-center">
                                <img class="col-3" src="http://localhost:8000/img/default.jpg">
                                <div class="col-9">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-10 dropdown-cart-name">Very nice product name yeeeeeeet</div>
                                        <i class="cart-remove col-2 bi bi-x-lg"></i>
                                    </div>
                                    <div class="row dropdown-cart-amount justify-content-between">
                                        <div class="col-2 px-0">
                                            5x 10$
                                        </div>
                                        <div class="col-2 item-subtotal">
                                            500$
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                @endif

                <!-- Notification -->
                <div class="dropdown mt-1">
                <a class="text-reset me-1 dropdown-toggle hidden-arrow" href="#" id="notification-dropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell" style="color: #000000; font-size:1.5em;"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="notification-dropdown">
                    <li><a class="dropdown-item" href="#">Some news</a></li>
                    <li><a class="dropdown-item" href="#">Another news</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
                </div>

                <!-- User -->
                <div class="dropdown">
                <a class="text-reset dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                    id="user-drodown" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(File::exists(public_path(Auth::user()->photo->url)))
                      <img src={{asset(Auth::user()->photo->url)}} class="rounded-circle" height="25" alt="" loading="lazy" />
                    @else
                      <img src="/img/user.png" class="rounded-circle" height="25" alt="" loading="lazy" />
                    @endif
                    <h5 class="px-3 mt-1" style="color: #000000">{{Auth::user()->name}}</h5>
                </a>
                <ul class="dropdown-menu" aria-labelledby="user-drodown">
                    @if(!Auth::user()->is_admin)
                      <li><a class="dropdown-item" href={{url("users/" . strval(Auth::user()->id))}}>My profile</a></li>
                    @endif
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><form method="POST" class="col" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item form-control" href="{{ url('/logout') }}"> Logout </button>
                  </form></li>
                </ul>
                </div>
            @else
                <a class="btn btn-outline-primary" href={{url('/join')}}>Sign-In</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

</nav>