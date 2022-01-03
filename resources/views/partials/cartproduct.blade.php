<div class="card my-3" style="height: 8em;">
    <div class="row g-6 h-100">
      <div class="col-md-2 h-100">
        <img src="{{asset($cart_item->photos[0]->url)}}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
      </div>
      <div class="col">
        <div class="card-body container-fluid">
          <div class="row">
            <div class="col">
              <h5 class="card-title">{{$cart_item->name}}</h5>
              <p class="card-text">{{$cart_item->description}}</p>
              <p class="card-text"><small class="text-muted">
                      @for ($i = 1; $i <= 5; $i++)
                          <i class="bi bi-star{{floor($cart_item->avg_stars) >= $i ? '-fill' : (ceil($cart_item->avg_stars) == $i ? '-half' : '')}}"></i>
                      @endfor</small></p>
            </div>
            <div class="col">
              <h5> Amount </h5>
              <p>{{$cart_item->details->amount}}</p>
            </div>
            <div class="col">
              <h5>Price</h5>
              <p> {{$cart_item->price}} €</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>