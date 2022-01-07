<div class="card my-3" style="height: 8em;">
    <div class="row g-6 h-100">
      <div class="col-md-2 h-100">
        <img src="{{asset($wishlistItem->photos[0]->url)}}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
      </div>
      <div class="col">
        <div class="card-body container-fluid">
          <div class="row">
            <div class="col">
              <h5 class="card-title">{{$wishlistItem->name}}</h5>
              <p class="card-text">{{$wishlistItem->description}}</p>
              <p class="card-text"><small class="text-muted">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{floor($wishlistItem->avg_stars) >= $i ? '-fill' : (ceil($wishlistItem->avg_stars) == $i ? '-half' : '')}}"></i>
                @endfor</small></p>
            </div>
            <div class="col">
              <h5>Price</h5>
              <p> {{$wishlistItem->price}} €</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>