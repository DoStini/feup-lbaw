<div class="card my-3" style="height: 8em;">
  <div class="row g-6 h-100">
    <div class="col-3 h-100">
      <a class="icon-click" href={{route('getProduct', ['id' => $wishlistItem->id])}}>
        <img src="{{asset($wishlistItem->photos[0]->url)}}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
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
            <a class="icon-click bi bi-info-circle col-2 pe-2 text-end" id="add-wishlist"
                    style="font-size:2em" href={{route('getProduct', ['id' => $wishlistItem->id])}}
                >
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>