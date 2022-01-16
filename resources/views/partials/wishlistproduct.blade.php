<div id="product-{{$wishlistItem->id}}-card" class="card my-3" style="height: 8em;">
  <div class="row g-6 h-100">
    <div class="col-3 pe-0 h-100">
      <a class="icon-click" href={{route('getProduct', ['id' => $wishlistItem->id])}} style="overflow: hidden;">
        <img src="{{asset($wishlistItem->photos[0]->url)}}" class="img-fluid rounded-start w-100 h-100" style="overflow: hidden;">
      </a>
    </div>
    <div class="col-9 h-100">
      <div class="card-body container-fluid h-100">
        <div class="row h-100">
          <div class="col-5 h-100">
            <h5 class="card-title h-50" style="overflow: hidden; text-overflow: ellipsis;">
              <a class="icon-click" href={{route('getProduct', ['id' => $wishlistItem->id])}}>
                {{$wishlistItem->name}}
              </a>
            </h5>
            <p class="card-text"><small class="text-muted">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{floor($wishlistItem->avg_stars) >= $i ? '-fill' : (ceil($wishlistItem->avg_stars) == $i ? '-half' : '')}}"></i>
                    @endfor</small></p>
          </div>
          <div class="col-5">
            <h5>Price</h5>
            <p> {{$wishlistItem->price}} €</p>
          </div>
          <div class="col-2">  
            <a class="icon-click bi bi-x-circle col-2 pe-2 text-end" id="remove-in-wishlist-{{$wishlistItem->id}}"
                    style="font-size:2em" 
                >
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("remove-in-wishlist-{{$wishlistItem->id}}").addEventListener("click", (e) => {
  removeFromWishlistRequest({{$wishlistItem->id}}, () => {
    document.getElementById("product-{{$wishlistItem->id}}-card").remove();
  });
});
</script>