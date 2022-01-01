@if($admin)
    <a href={{route('editPage', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Edit Personal Data </a>
    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Delete Account </a>
    <form  method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
    </form>
@elseif (Auth::check() && Auth::user()->id == $shopper->user->id)
    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100"> About Me </a>
    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Wishlist </a>
    <a href={{route('editPage', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Edit Personal Data </a>
    <a href={{route('addresses', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Manage Addresses </a>
    <a href={{route('getOrders')}} class="my-2 btn btn-primary w-100">  Purchase History </a>
    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Furniture Offers </a>
    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Delete Account </a>
    <form  method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
    </form>
@else
    <a class="my-2 btn btn-primary w-100" href={{route('getUser', ['id' => $shopper->user->id])}}> About {{$shopper->user->name}} </a>
    <a class="my-2 btn btn-primary w-100" href={{route('getUser', ['id' => $shopper->user->id])}}> {{$shopper->user->name}}'s Wishlist </a>
@endif