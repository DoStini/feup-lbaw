<!--<article class="shopper" data-id="{{ $shopper->id }}">
    <h1>{{$shopper->user->name}}</h1>
    <h3> My funny number </h3>
    <p>{{$shopper->phone_number}}</p>
    <h3> My funny email </h3>
    <p>{{$shopper->user->email}}</p>
    <h3>About me</h3>
    <p> {{$shopper->about_me}}</p>
    <h3>Addresses</h3>
    @each('partials.address', $shopper->addresses, 'address')
    <h3>Orders</h3>
    @each('partials.order', $shopper->orders, 'order')
</article>-->

<div class="container h-100 my-3">
    <div class="row">
        <div class="col-md-3 col-sm-12 container">
            <div class="d-flex justify-content-center align-items-center">
                <div class="w-50">
                    @if (File::exists(Storage::url($shopper->user->photo->url)))
                        <img src={{Storage::url($shopper->user->photo->url)}} class="img-fluid" alt="" loading="lazy" />
                    @else
                        <img src="/img/user.png" class="img-fluid" alt="" loading="lazy" />
                    @endif
                </div>
            </div>
            <div class="my-3 mx-2">
                @if (!Auth::user()->is_admin && Auth::user()->id == $shopper->user->id)
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100"> About Me </a>
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100">  Wishlist </a>
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100">  Edit Personal Data </a>
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100">  Purchase History </a>
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100">  Furniture Offers </a>
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100">  Delete Account </a>
                    <form  method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
                    </form>
                @else
                    <button type="button" class="my-2 btn btn-primary w-100" onclick="window.location='{{ url("users/" . strval($shopper->user->id))}}'""> About {{$shopper->user->name}} </button>
                    <button type="button" class="my-2 btn btn-primary w-100" onclick="window.location='{{ url("users/" . strval($shopper->user->id)) }}'""> {{$shopper->user->name}}'s Wishlist </button>
                @endif

            </div>
        </div>
        <div class="col-md-9 col-sm-12 container">
            <div>
                <h3>About {{$shopper->user->name}}</h3>
                <p>{{$shopper->about_me}}</p>
            </div>
            <div>
                <h3>Placeholder for Reviews</h3>
            </div>
        </div>
    </div>
</div>