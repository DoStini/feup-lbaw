
<section id="cart h-100">
    @if($shopper->wishlist->isEmpty())
        @if(Auth::check() && $shopper->id === Auth::user()->id)
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h3>You have no items in your wishlist.</h3>
            <form action="/products">
                <button type="submit" class="btn btn-primary">Go to Product List</button>
            </form>
        </div>
        @else
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h3>This user has no items in their wishlist.</h3>
        </div>
        @endif
    @else
    <h3 class="mx-2 text-center d-block">{{$shopper->user->name}}'s Wishlist</h3>
    @php
        $wishlist =  $shopper->wishlist()->paginate(4);
    @endphp
    <div class="container-fluid px-md-5">
        <div class="row">
            @foreach ($wishlist as $wishlistItem)
                @include('partials.wishlistproduct', ["wishlistItem" => $wishlistItem, "shopper" => $shopper])
            @endforeach
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-end px-md-5">{{$wishlist->links()}}</div>
    @endif
</section>
