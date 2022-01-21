<div class="col-lg-4 col-md-6 col-xs-12" style="visibility: visible">
    <div id="product-{{$product->id}}" class="card mb-5 search-products-item">
        <img class="card-img-top" src="{{$product->photos[0]->url}}" alt="Product Image of {{$product->name}}" onerror="this.src='/img/default.jpg'">
        <div class="card-body">
            <h4 class="card-title">{{ucfirst($product->name)}}</h4>
            <div class="container ps-0 pe-0">
                <div class="row justify-content-between align-items-center">
                    <h4 class="col mb-0">{{$product->price}} &euro;</h4>
                </div>
            </div>
        </div>
    </div>
</div>
