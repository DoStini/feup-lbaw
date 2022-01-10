@if($admin)
    {{-- <a href={{route('editPage', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Edit Personal Data </a> --}}
    {{--<a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Delete Account </a>--}}
    <form  method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
    </form>
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
    </ul>

    {{--<a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Wishlist </a>--}}
    {{--<a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Furniture Offers </a>
    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Delete Account </a>--}}
    <form  method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
    </form>
@elseif(Auth::user()->is_admin && $shopper)
    <a class="my-2 btn btn-primary w-100" href={{route('getUser', ['id' => $shopper->user->id])}}> About {{$shopper->user->name}} </a>
    <a href={{route('editPage', ['id' => $shopper->user->id])}} class="my-2 btn btn-primary w-100">  Edit {{$shopper->user->name}}'s Personal Data </a>
    {{--<a class="my-2 btn btn-primary w-100" href={{route('getUser', ['id' => $shopper->user->id])}}> {{$shopper->user->name}}'s Wishlist </a>--}}
@else
    <a class="my-2 btn btn-primary w-100" href={{route('getUser', ['id' => $shopper->user->id])}}> About {{$shopper->user->name}} </a>
    {{--<a class="my-2 btn btn-primary w-100" href={{route('getUser', ['id' => $shopper->user->id])}}> {{$shopper->user->name}}'s Wishlist </a>--}}
@endif