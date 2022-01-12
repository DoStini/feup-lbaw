<div class="card my-3" style="height: 8em;">
    <div class="row g-6 h-100">
      <div class="col-2 h-100">
        <a class="icon-click" href={{route('getProduct', ['id' => $cart_item->id])}}>
          <img src="{{asset($cart_item->photos[0]->url)}}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
        </a>
      </div>
      <div class="col-10 h-100">
        <div class="card-body container-fluid h-100">
          <div class="row h-100">
            <div class="col-4 h-100">
              <h5 class="card-title h-50" style="overflow: hidden; text-overflow: ellipsis;">
                <a class="icon-click" href={{route('getProduct', ['id' => $cart_item->id])}}>
                  {{$cart_item->name}}
                </a>
              </h5>
              <p class="card-text"><small class="text-muted">
                      @for ($i = 1; $i <= 5; $i++)
                          <i class="bi bi-star{{floor($cart_item->avg_stars) >= $i ? '-fill' : (ceil($cart_item->avg_stars) == $i ? '-half' : '')}}"></i>
                      @endfor</small></p>
            </div>
            <div class="col-4">
              <h5> Amount </h5>
              <p>{{$cart_item->details->amount}}</p>
            </div>
            <div class="col-4">
              <h5>Price</h5>
              <p> {{$cart_item->price}} €</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>