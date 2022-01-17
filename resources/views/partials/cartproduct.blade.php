<div class="card container my-3 p-0 cart-product-container">
    <div class="row g-6">
        <div class="col-md-2 col-12">
            <a class="icon-click" href={{route('getProduct', ['id'=> $cart_item->id])}} >
                <img src="{{asset($cart_item->photos[0]->url)}}"
                    class="img-fluid rounded-start w-100 h-100 cart-product-image">
            </a>
        </div>
        <div class="col-md-10">
            <div class="card-body container-fluid w-100">
                <div class="row h-100">
                    <div class="col-md-4 col-12 my-md-0 my-4 h-100">
                        <h5 class="card-title text-center" style="overflow: hidden; text-overflow: ellipsis;">
                            <a class="icon-click" href={{route('getProduct', ['id'=> $cart_item->id])}}>
                                {{$cart_item->name}}
                            </a>
                        </h5>
                        <p class="card-text text-center"><small class="text-muted">
                                @for ($i = 1; $i <= 5; $i++) <i
                                    class="bi bi-star{{floor($cart_item->avg_stars) >= $i ? '-fill' : (ceil($cart_item->avg_stars) == $i ? '-half' : '')}}">
                                    </i>
                                    @endfor</small></p>
                    </div>
                    <div id="amount" class="col-md-3 col-4 p-md-0">
                        <h5 class="text-center"> Amount </h5>
                        <div class="d-flex justify-content-center" id="cart-number-selector-{{$cart_item->id}}" data-id={{$cart_item->id}}
                            data-amount={{$cart_item->details->amount}} data-stock={{$cart_item->stock}}>

                        </div>
                    </div>
                    <div class="col-md-3 col-4 p-md-0">
                        <h5 class="text-center">Price</h5>
                        <p class="text-center" id="cart-total-price-{{$cart_item->id}}"> {{$cart_item->price}} €</p>
                    </div>
                    <div class="col-md-2 col-4 d-flex justify-content-center align-items-center p-md-0">
                        <a class="icon-click bi bi-x-circle col-2 pe-2 text-end text-danger"
                            id="remove-in-cart-{{$cart_item->id}}" style="font-size:2em">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById("remove-in-cart-{{$cart_item->id}}").addEventListener("click", (e) => {
    deleteRequest(`/api/users/cart/{{$cart_item->id}}/remove`)
    .then(response => {
        if (response.status === 200) {
        location.reload();
        }
    });
});
</script>
