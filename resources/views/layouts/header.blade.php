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
          <div class="d-flex">
            @if(Auth::check())
                <!-- Cart -->
                <a class="text-reset me-5 mt-1" href="/users/cart">
                <span><i class="fas fa-shopping-cart" style="color: #000000; font-size:1.5em;"></i></span>
                </a>

                <!-- Notification -->
                <div class="dropdown mt-1">
                <a class="text-reset me-1 dropdown-toggle hidden-arrow" href="#" id="dropdownMenuButton2"
                data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell" style="color: #000000; font-size:1.5em;"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    <li><a class="dropdown-item" href="#">Some news</a></li>
                    <li><a class="dropdown-item" href="#">Another news</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
                </div>

                <!-- User -->
                <div class="dropdown">
                <a class="text-reset dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://mdbootstrap.com/img/new/avatars/1.jpg" class="rounded-circle" height="25" alt=""
                    loading="lazy" />
                    <h5 class="px-3 mt-1" style="color: #000000">{{Auth::user()->name}}</h5>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item" href="#">My profile</a></li>
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