<!-- Navbar -->
@include('partials.alert')

<nav>
  <div class="p-3 text-center text-white">
    <div class="container">
      <div class="row d-flex align-items-center justify-content-between">
        <div class="col-md-4 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0">
          <a href={{route('home')}} class="ms-md-2">
            <img src="/img/refurniture.svg" alt="reFurniture Logo" width="200" height="65" />
          </a>
        </div>

        <div class="col-md-4">
          <form id="search-products-form" class="d-flex input-group w-auto my-auto mb-3 mb-md-0">
            <input id="search-products-input" autocomplete="off" type="search" class="form-control rounded" placeholder="Search" />
          </form>
        </div>

        <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
          <div class="d-flex align-items-center justify-content-between" id="header-icons">
            @if(Auth::check())
                @if(!Auth::user()->is_admin)
                @include("partials.dropdowncart")
                <div>
                  <a href={{route('getWishlistPage')}}>
                    <i class="bi bi-bookmark-heart-fill" style="color: #000000; font-size:1.5em;"></i>
                  </a>
                </div>
                <!-- Notification -->
                <div class="dropdown">
                    <a class="text-reset me-1 hidden-arrow" href="#" id="notification-dropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill" style="color: #000000; font-size:1.5em;"></i>
                    </a>
                    <ul id="notification-content" class="dropdown-menu dropdown-menu-end" aria-labelledby="notification-dropdown">
                    </ul>
                </div>

                <div class="notification-number">
                    <div class="new-notif"></div>
                </div>
                @endif
                <!-- User -->
                <div class="dropdown">
                <a class="text-reset d-flex align-items-center hidden-arrow" href="#"
                    id="user-drodown" data-bs-toggle="dropdown" aria-expanded="false">
                    @if(File::exists(public_path(Auth::user()->photo->url)))
                      <img id="header-user-img" src={{asset(Auth::user()->photo->url)}} class="profile-pic rounded-circle" height="25" width="25" alt="Profile Picture" loading="lazy" />
                    @else
                      <img id="header-user-img" src="/img/user.png" class="profile-pic rounded-circle" height="25" alt="Default Profile Picture" loading="lazy" />
                    @endif
                    <h5 id="header-user-name" class="px-3 mt-1" style="color: #000000;">
                      {{strlen(explode(" ", Auth::user()->name)[0]) > 13 ?
                        substr(explode(" ", Auth::user()->name)[0], 0, 10) . '...' :
                        explode(" ", Auth::user()->name)[0]}}
                    </h5>
                </a>
                <ul class="dropdown-menu" aria-labelledby="user-drodown">
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
        const notificationNumber = document.querySelector(".notification-number");
        const notificationContent = document.getElementById("notification-content");

        let skip = 0;

        const removeButton = () => {
            const prev = document.getElementById("next-page-btn");
            if (prev) {
                prev.remove();
            }
        }

        const createButton = () => {
            const button = buildNextNotificationButton();
            button.addEventListener('click', (e) => {
                get(`/api/users/{{Auth::user()->id}}/notifications?skip=${skip}`)
                .then(data => {
                    handleNextRequest(data.data);
                });
                e.stopPropagation();
            });
            notificationContent.appendChild(button);
        }

        function addParsed(parsed, content) {
            parsed.appendChild(getDivider());
            content.appendChild(parsed);
        }

        function prependParsed(parsed, content) {
            parsed.appendChild(getDivider());
            content.prepend(parsed);
        }

        const handleNextRequest = (data) => {
            const notifications = data.notifications;
            notifications.forEach(noti => {
                const notif = parseNotification(noti);
                addParsed(notif, notificationContent);
            });

            if (data.new_nots > 0) {
                notificationNumber.style.visibility = "visible";
            }

            skip += notifications.length;

            removeButton();

            if (skip < data.total) {
                createButton();
            }
        }

        const handleNewRequest = (data) => {
            const notifications = data.notifications;
            notifications.forEach((noti, idx) => {
                const item = parseNotification(noti);
                if (item) {
                    addParsed(item, notificationContent);
                }
            });

            skip += notifications.length;
            removeButton();

            if (skip < data.total) {
                createButton();
            }
        }


        get(`/api/users/{{Auth::user()->id}}/notifications`)
            .then(data => {
                handleNewRequest(data.data)

                if (data.data.new_nots > 0) {
                  notificationNumber.style.visibility = "visible";
                }
            });


        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;

        const pusher = new Pusher('4c7db76f6f7fd6381f0e', {
            cluster: 'eu'
        });

        const handlePusherNotification = () => {
            skip++;
            notificationNumber.style.visibility = "visible";
            try{
              const audio = new Audio('/sounds/notif.wav');
              audio.play();
            } catch (e) {
              console.log(e);
            }

        }

        const channelProfileEdited = pusher.subscribe("profile-edited");
        channelProfileEdited.bind("profile-edited-{{Auth::user()->id}}", function(data) {
            handlePusherNotification();
            const notif = buildEditedNotifcation(data);
            prependParsed(notif, notificationContent);
        });

        const channelOrderStatus = pusher.subscribe("order-status");
        channelOrderStatus.bind("order-status-{{Auth::user()->id}}", function(data) {
            handlePusherNotification();
            const notif = buildOrderNotification(data);
            prependParsed(notif, notificationContent);
        });

        const cartItemUpdated = pusher.subscribe("cart-item");
        cartItemUpdated.bind("cart-item-{{Auth::user()->id}}", function(data) {
            handlePusherNotification();
            const notif = buildCartNotification(data);
            prependParsed(notif, notificationContent);
        });

        const wishlistItemUpdated = pusher.subscribe("wishlist-item");
        wishlistItemUpdated.bind("wishlist-item-{{Auth::user()->id}}", function(data) {
            handlePusherNotification();
            const notif = buildWishlistNotification(data);
            prependParsed(notif, notificationContent);
        });

        notification.addEventListener("click", () => {
            formDataPost(`/api/users/{{Auth::user()->id}}/notifications/`)
                .then(data => {
                    notificationNumber.style.visibility = "hidden";
                });
        });

    });

</script>

@endif
