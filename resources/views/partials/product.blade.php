@if($errors->any())
<script async>
    (async() => {
        while(!window.hasOwnProperty('reportData'))
            await new Promise(resolve => setTimeout(resolve, 100));

        let errors = JSON.parse(`<?php echo($errors->toJson()) ?>`.replace(/\s+/g," "));

        reportData("Couldn't create the product", errors, {
            'product_id': 'Product ID',
            'text': 'Review Body',
            'stars': 'Rating',
            'photos': 'Photos'
        });

        setRating({{old("stars")}})
    })();
</script>
@endif


<div class="product container vw-100" data-id={{ $product->id }}>
    <div class="row w-100">
        <div class="product-images mt-4 col-md-7 d-flex justify-content-center">
            <div id="productCarousel" class="carousel slide product-slide product-carousel w-100" data-bs-ride="carousel">
                <div class="carousel-inner w-100 product-carousel">
                    @php
                        $insertedPhotos = 0;
                    @endphp
                    @if ($product->photos)
                    @foreach ($product->photos as $photo)
                    @if (File::exists(public_path($photo->url)) || filter_var($photo->url, FILTER_VALIDATE_URL))
                    <div class="carousel-item w-100 {{$loop->iteration == 1 ? 'active' : '' }}">
                        <img class="d-block w-100" src={{$photo->url}}>
                    </div>
                    {{$insertedPhotos++;}}
                    @endif
                    @endforeach
                    @endif
                    @if ($insertedPhotos < 1)
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="/img/default.jpg">
                    </div>
                    @endif
                </div>
                @if ($insertedPhotos > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" style="background-color: rgb(99, 99, 99); border-radius: 25%;" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" style="background-color: rgb(99, 99, 99); border-radius: 25%;" aria-hidden="true"></span>
                </button>
                @endif
            </div>
        </div>
        <div class="product-info col-md-5">
            <div class="my-3">
                <h2 class="m-0" style=text-align: justify;">{{strtoupper($product->name)}}</h2>
                <p>
                    @for ($i = 1; $i <= 5; $i++) <i
                        class="bi bi-star{{floor($product->avg_stars) >= $i ? '-fill' : (ceil($product->avg_stars) == $i ? '-half' : '')}}">
                        </i>
                        @endfor
                    </p>
            </div>

            <div class="my-2 d-flex justify-content-between align-items-center">
                <h3> {{$product->price}} €</h3>
                @if(Auth::check() && !Auth::user()->is_admin)
                <i class="add-wishlist icon-click bi bi-heart col-2 pe-2 text-end" id="add-wishlist"
                    style="font-size:2em;@if($wishlisted)display:none @endif"
                >
                </i>
                <i class="remove-wishlist icon-click bi bi-heart-fill col-2 pe-2 text-end" id="remove-wishlist"
                    style="font-size:2em;@if(!$wishlisted)display:none @endif"
                >
                </i>
                @endif
            </div>

            <div id="description-box-teaser" class="description-box-teaser">
                <p style=text-align: center;" id="description-text-teaser">{{$product->description}}</p>
                <div class="show-more" id="show-more-btn">
                    <i class="bi bi-arrow-down-circle" id="show-more-button"></i>
                </div>
            </div>

            <div id="description-box-full" class="description-box-full">
                <p style=text-align: center;">{{$product->description}}</p>
                <div class="show-less">
                    <i class="bi bi-arrow-up-circle" id="show-less-button"></i>
                </div>
            </div>

            @if(get_object_vars(json_decode($product->attributes)))
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h5> Color: {{json_decode($product->attributes)->color}} </h5>
                        </div>
                        <div class="col-4 d-flex justify-content-end">
                            <div class="btn-group dropstart">
                                <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Variations
                                </button>
                                <div class="dropdown-menu" style="width: 220px; height: 200px;">
                                @php
                                    $color_id_pair = json_decode($product->attributes)->variants
                                @endphp
                                <div class="container w-100 h-100 overflow-auto">
                                    @foreach ($color_id_pair as $id => $color)
                                        @if($loop->first)
                                            <div class="row my-1">
                                        @elseif(($loop->iteration - 1) % 3 == 0)
                                            </div> <div class="row my-1">
                                        @elseif($loop->last)
                                            </div>
                                        @endif
                                    <a class="col-4" href={{route('getProduct', ['id' => $id])}} data-toggle="tooltip" data-placement="top" title={{$color}} ><img class="variant-color" src={{sprintf("https://cdn.shopify.com/s/files/1/0014/1865/7881/files/%s_50x50_crop_center.png", $color)}} onerror="this.src='{{asset('img/notfound.jpg')}}'"></a>
                                @endforeach
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="quantity-wishlist my-4 justify-content-between align-items-center d-flex">
                @if ($product->stock > 0)
                <div id="quantity-container" class="w-25">
                </div>
                <div class="calculated-price">
                    <h6 id="current-price">Subtotal: {{$product->price}} €</h6>
                </div>
                @endif

                <!--
                    <i class="bi bi-heart-fill add-to-wishlist"></i>
                    -->
            </div>

            <div class="product-actions d-flex flex-column my-4 justify-content-center align-items-center">
                @if(Auth::check() && !Auth::user()->is_admin && $product->stock > 0)
                <button id="add-to-cart-btn" class="btn btn-primary w-100 my-2">Add to Cart</button>
                @elseif((!Auth::check() || !Auth::user()->is_admin) && $product->stock > 0)
                <a href="{{route('join')}}" id="add-to-cart-btn" class="btn btn-primary w-100 my-2">Login to add to Cart</a>
                @endif
                @if(Auth::check() && Auth::user()->is_admin)
                <form class="w-100" action="{{route('editProductPage', ['id' => $product->id])}}">
                    <button type="submit" class="btn btn-primary w-100 my-2">Edit this product</button>
                </form>
                @endif
                @if ($product->stock > 0)
                <button class="btn btn-success w-100 my-2" disabled>In Stock</button>
                @else
                <button class="btn btn-danger w-100 my-2" disabled>Out of Stock</button>
                @endif
            </div>
        </div>
    </div>
</div>
@can('reviewProduct', [App\Models\Review::class, $product])
<div class="container d-flex flex-column justify-content-around mt-4 mb-4">
    <form id="add-review-form" enctype="multipart/form-data" class="container" action={{route('addReview', ["product_id" => $product->id])}} method="POST">
        @csrf
        <div class="row d-flex justify-content-between align-items-baseline">
            <h2 class="w-auto">Add a Review</h2>
            <div class="w-auto fs-4">
                <span class="fs-5 mr-5">Rate the product: </span>
                <i id="review-form-star-1" class="bi bi-star" onclick="setRating(1)"></i>
                <i id="review-form-star-2" class="bi bi-star" onclick="setRating(2)"></i>
                <i id="review-form-star-3" class="bi bi-star" onclick="setRating(3)"></i>
                <i id="review-form-star-4" class="bi bi-star" onclick="setRating(4)"></i>
                <i id="review-form-star-5" class="bi bi-star" onclick="setRating(5)"></i>
            </div>
            <input id="review-form-star" type="hidden" name="stars" value="0" required>
        </div>
        <div class="row">
            <div class="form-group">
                <label for="review-text">Review Body</label>
                <textarea id="review-text" class="form-control" style="height: 6em" name="text" required>{{old('text')}}</textarea>
                <span class="error form-text text-danger" id="text-error"></span>
            </div>
        </div>
        <div class="row d-flex justify-content-between align-items-end mt-1">
            <div class="form-group col-6">
                <label for="review-form-photos">Review Photos</label>
                <input type="file" class="form-control" name="photos[]" id="review-form-photos"  multiple>
            </div>

            <span class="w-auto">
                <button type="submit" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </span>
        </div>
    </form>
    {{-- <button class="row btn btn-primary">VIEW REVIEWS</button> --}}

</div>

<script type="application/javascript">
function setRating(index) {
    const start = document.getElementById("review-form-star")

    if(parseInt(start.value) === index) {
        index = 0;
    }

    start.value = index;

    for(let i = 1; i <= 5; i++) {
        const starElement = document.getElementById("review-form-star-" + i);

        if(i <= index) {
            starElement.classList.remove("bi-star");
            starElement.classList.add("bi-star-fill");
        } else {
            starElement.classList.remove("bi-star-fill");
            starElement.classList.add("bi-star");
        }
    }
}
</script>
@endcan
