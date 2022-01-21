
@if($errors->any())
<script async>
    (async() => {
        while(!window.hasOwnProperty('reportData'))
            await new Promise(resolve => setTimeout(resolve, 100));

        let errors = JSON.parse(`<?php echo($errors->toJson())?>`);

        reportData("Couldn't delete the account", errors, {
            "cur-password": "Current Password",
            "id": "ID",
        });
    })();
</script>
@endif

@if($admin)
    <form  method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
    </form>
    <a id="show-delete-confirm" class="my-2 btn btn-danger w-100">  Delete Account </a>

@elseif (Auth::check() && Auth::user()->id == $shopper->user->id)
    <ul class="nav nav-tabs v-nav-tabs flex-column mb-5">
        <li class="nav-item">
            <a class="nav-link{{($page == 'aboutShopper' ? ' active' : '')}}" href={{route('getUser', ['id' => Auth::user()->id])}}> About Me </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{($page == 'editUser' ? ' active' : '')}}" href={{route('editPage', ['id' => Auth::user()->id])}}>  Edit Personal Data </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{($page == 'addresses' ? ' active' : '')}}" href={{route('addresses', ['id' => Auth::user()->id])}}>  Manage Addresses </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{($page == 'showShopperOrders' ? ' active' : '')}}" href={{route('getOrders')}}>  Purchase History </a>
        </li>
        <li class="nav-item">
            <a class="nav-link{{($page == 'wishlist' ? ' active' : '')}}" href={{route('getWishlistPage')}}> Wishlist </a>
        </li>
    </ul>

    {{--<a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Furniture Offers </a>
    --}}
    <form  method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
    </form>
    <a id="show-delete-confirm" class="my-2 btn btn-danger w-100">  Delete Account </a>
@elseif(Auth::user()->is_admin && $shopper)
    <ul class="nav nav-tabs v-nav-tabs flex-column mb-5">
        <li class="nav-item">
            <a class="nav-link {{($page == 'aboutShopper' ? ' active' : '')}}" href={{route('getUser', ['id' => $shopper->user->id])}}> About {{$shopper->user->name}} </a>

        </li>
        <li class="nav-item">
            <a class="nav-link {{($page == 'editUser' ? ' active' : '')}}" href={{route('editPage', ['id' => $shopper->user->id])}} >  Edit {{$shopper->user->name}}'s Personal Data </a>

        </li>
        <li class="nav-item">
            <a class="nav-link{{($page == 'wishlist' ? ' active' : '')}}" href={{route('getWishlist', ['id' => $shopper->id])}}> {{$shopper->user->name}}'s' Wishlist </a>

        </li>
    </ul>

@else
<ul class="nav nav-tabs v-nav-tabs flex-column mb-5">
    <li class="nav-item">
        <a class="nav-link {{($page == 'aboutShopper' ? ' active' : '')}}" href={{route('getUser', ['id' => $shopper->user->id])}}> About {{$shopper->user->name}} </a>
    </li>
    <li class="nav-item">
        <a class="nav-link{{($page == 'wishlist' ? ' active' : '')}}" href={{route('getWishlist', ['id' => $shopper->id])}}> {{$shopper->user->name}}'s' Wishlist </a>
    </li>
</ul>

@endif

