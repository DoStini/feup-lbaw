<div class="card my-3 @if($cart_item->details->amount > $cart_item->stock) border-danger shadow-sm @endif" style="height: 8em;">
    @if($cart_item->details->amount > $cart_item->stock)
    <div style="position: absolute; background-color: rgba(0,0,0,0.1); height: 100%; z-index: 91234919243" class="w-100 d-flex justify-content-center align-items-center">
        <h1 style="display: block;color: red">OUT OF STOCK</h1>
    </div>
    @endif
    <div class="row g-6 h-100">
      <div class="col-2 h-100">
        <img src="{{asset($cart_item->photos[0]->url)}}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
      </div>
      <div class="col-10 h-100">
        <div class="card-body container-fluid h-100">
          <div class="row h-100">
            <div class="col-4 h-100">
              <h5 class="card-title h-50" style="overflow: hidden; text-overflow: ellipsis;">{{$cart_item->name}}</h5>
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
