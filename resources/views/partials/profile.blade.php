<div class="container h-100 my-3">
    <div class="row">
        <div class="col-md-3 col-sm-12 container">
            <div class="d-flex justify-content-center align-items-center">
                <div class="w-50">
                    @php
                        $photo_url = null;
                        if($admin) $photo_url = $admin->photo->url;
                        else $photo_url = $shopper->user->photo->url; 
                    @endphp
                    @if (File::exists(public_path($photo_url)))
                        <img id="user-img" src={{asset($photo_url)}} class="img-fluid" alt="" loading="lazy" />
                    @else
                        <img id="user-img" src="/img/user.png" class="img-fluid" alt="" loading="lazy" />
                    @endif
                </div>
            </div>
            <div class="my-3 mx-2">
                @if($admin)
                    <a href={{url("users/" . strval(Auth::user()->id) . "/private")}} class="my-2 btn btn-primary w-100">  Edit Personal Data </a>
                    <a href={{url("users/" . strval(Auth::user()->id))}} class="my-2 btn btn-primary w-100">  Delete Account </a>
                    <form  method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="my-2 btn btn-primary form-control"> Logout </button>
                    </form>
                @elseif (Auth::check() && Auth::user()->id == $shopper->user->id)
                    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100"> About Me </a>
                    <a href={{route('getUser', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Wishlist </a>
                    <a href={{route('editPage', ['id' => Auth::user()->id])}} class="my-2 btn btn-primary w-100">  Edit Personal Data </a>
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

            </div>
        </div>
        <div class="col-md-9 col-sm-12 container">
            @include('partials.' . $page, ['shopper' => $shopper])
        </div>
    </div>
</div>