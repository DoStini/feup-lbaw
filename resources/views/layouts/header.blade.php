<!-- Navbar -->
<nav>
  <div class="p-3 text-center text-white">
    <div class="container">
      <div class="row d-flex align-items-center">
        <div class="col-md-4 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0">
          <a href={{route('getProductSearch')}} class="ms-md-2">
            <img src="/img/refurniture.svg" alt="" width="200" height=65" />
          </a>
        </div>

        <div class="col-md-4">
          <form id="search-products-form" class="d-flex input-group w-auto my-auto mb-3 mb-md-0">
            <input id="search-products-input" autocomplete="off" type="search" class="form-control rounded" placeholder="Search" />
            <span class="input-group-text border-0 d-none d-lg-flex"><i class="fas fa-search"></i></span>
          </form>
        </div>

        <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
          <div class="d-flex align-items-center">
            @if(Auth::check())
                @if(!Auth::user()->is_admin)
                <!-- Cart -->
                @include("partials.dropdowncart")

                <!-- Notification -->
                <div class="dropdown">
                    <a class="text-reset me-1 dropdown-toggle hidden-arrow" href="#" id="notification-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell" style="color: #000000; font-size:1.5em;"></i>
                    </a>
                    <ul id="notification-content" class="dropdown-menu" aria-labelledby="notification-dropdown">
                    </ul>
                </div>

                @endif

                <!-- User -->
                <div class="dropdown">
                <a class="text-reset dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                    id="user-drodown" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(File::exists(public_path(Auth::user()->photo->url)))
                      <img id="header-user-img" src={{asset(Auth::user()->photo->url)}} class="rounded-circle" height="25" width="25" alt="" loading="lazy" />
                    @else
                      <img id="header-user-img" src="/img/user.png" class="rounded-circle" height="25" alt="" loading="lazy" />
                    @endif
                    <h5 id="header-user-name" class="px-3 mt-1" style="color: #000000;">
                      {{strlen(explode(" ", Auth::user()->name)[0]) > 13 ? 
                        substr(explode(" ", Auth::user()->name)[0], 0, 10) . '...' :
                        explode(" ", Auth::user()->name)[0]}}
                    </h5>
                </a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    @if(Auth::user()->is_admin)
                      <li><a class="dropdown-item" href={{route('getDashboard')}}>Dashboard</a></li>
                    @else
                      <li><a class="dropdown-item" href={{route('getUser', ['id' => Auth::user()->id])}}>My profile</a></li>
                    @endif
                    <li><a class="dropdown-item" href={{route('editPage', ['id' => Auth::user()->id])}}>Settings</a></li>
                    <li><form method="POST" class="col" action="{{ route('logout') }}">
                      @csrf
                      <button type="submit" class="dropdown-item form-control"> Logout </button>
                  </form></li>
                </ul>
                </div>
            @else
                <a class="btn btn-outline-primary" href={{route('join')}}>Sign-In</a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

</nav>

@if(Auth::check() && !Auth::user()->is_admin)

<script>
	window.addEventListener("load", () => {
        const notification = document.querySelector("#notification-dropdown i");
        
        get(`/api/users/{{Auth::user()->id}}/notifications/`)
            .then(data => {
                console.log("DATA", data)
                data.data.notifications.forEach(noti => parseNotification(noti))
                if (data.data.new_nots > 0) {
                    notification.style.color = "red";
                }
            });

        
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        const pusher = new Pusher('4c7db76f6f7fd6381f0e', {
            cluster: 'eu'
        });
        const channel = pusher.subscribe("profile-edited");
        channel.bind("profile-edited-{{Auth::user()->id}}", function(data) {
            notification.style.color = "red";
            buildEditedNotifcation(data.message)
        });

        notification.addEventListener("click", () => {
            formDataPost(`/api/users/{{Auth::user()->id}}/notifications/`)
                .then(data => {
                    console.log(data);
                    notification.style.color = "black";
                });
        });

    });

</script>

@endif