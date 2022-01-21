<div id="product-{{$wishlistItem->id}}-card" class="card container my-3 p-0 cart-product-container">
    <div class="row g-6">
        <div class="col-md-3 col-12">
            <a class="icon-click" href={{route('getProduct', ['id'=> $wishlistItem->id])}}>
                <img src="{{asset($wishlistItem->photos[0]->url)}}"
                    class="img-fluid rounded-start w-100 h-100 cart-product-image" alt="Wishlist Product Photo of {{$wishlistItem->name}}">
            </a>
        </div>
        <div class="col-md-9 col-12 h-100">
            <div class="card-body container-fluid h-100">
                <div class="row h-100">
                    <div class="col-4 h-100">
                        <h5 class="card-title text-center h-50" style="overflow: hidden; text-overflow: ellipsis;">
                            <a class="icon-click" href={{route('getProduct', ['id'=> $wishlistItem->id])}}>
                                {{$wishlistItem->name}}
                            </a>
                        </h5>
                        <p class="card-text text-center"><small class="text-muted">
                                @for ($i = 1; $i <= 5; $i++) <i
                                    class="bi bi-star{{floor($wishlistItem->avg_stars) >= $i ? '-fill' : (ceil($wishlistItem->avg_stars) == $i ? '-half' : '')}}">
                                    </i>
                                    @endfor</small></p>
                    </div>
                    <div class="col-4">
                        <h5 class="text-center">Price</h5>
                        <p class="text-center"> {{$wishlistItem->price}} €</p>
                    </div>
                    @if(Auth::check() && $shopper->id === Auth::user()->id)

                    <div class="col-4 d-flex justify-content-center">
                        <a class="icon-click bi bi-x-circle col-2 pe-2 text-end text-danger"
                            id="remove-in-wishlist-{{$wishlistItem->id}}" style="font-size:2em">
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::check() && $shopper->id === Auth::user()->id)

<script>
    document.getElementById("remove-in-wishlist-{{$wishlistItem->id}}").addEventListener("click", (e) => {
  removeFromWishlistRequest({{$wishlistItem->id}}, () => {
    location.reload();
  });
});
</script>
@endif
