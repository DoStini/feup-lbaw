<div class="product container vw-100" data-id={{ $product->id }}>
    <div class="row w-100">
        <div class="product-images mt-4 col-md-7 d-flex justify-content-center">
            <div id="productCarousel" class="carousel slide product-slide product-carousel" data-bs-ride="carousel">
                <div class="carousel-inner product-carousel">
                    {{$insertedPhotos = 0;}}
                    @if ($product->photos)
                    @foreach ($product->photos as $photo)
                    @if (File::exists(public_path($photo->url)) || filter_var($photo->url, FILTER_VALIDATE_URL))
                    <div class="carousel-item {{$loop->iteration == 1 ? 'active' : '' }}">
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
                <p>
                    @for ($i = 1; $i <= 5; $i++) <i
                        class="bi bi-star{{floor($product->avg_stars) >= $i ? '-fill' : (ceil($product->avg_stars) == $i ? '-half' : '')}}">
                        </i>
                        @endfor
                    </p>
                <h2 style=text-align: justify;">{{strtoupper($product->name)}}</h2>
            </div>

            <div class="my-2 d-flex justify-content-between align-items-center">
                <h3> {{$product->price}} €</h3>
                <h5> Stock: {{$product->stock}} </h5>
            </div>

            <div id="description-box-teaser" class="description-box-teaser">
                <p style=text-align: center;">{{$product->description}}</p>
                <div class="show-more">
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
                        <div class="col-md-8">
                            <h5> Color: {{json_decode($product->attributes)->color}} </h5>
                        </div>
                        <div class="col-md-4">
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
                @if(Auth::check() && !Auth::user()->is_admin)
                <button id="add-to-cart-btn" class="btn btn-primary w-100 my-2">Add to Cart</button>
                @elseif(!Auth::check() || !Auth::user()->is_admin)
                <a href="{{route('join')}}" id="add-to-cart-btn" class="btn btn-primary w-100 my-2">Login to add to Cart</a>
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

@include('partials.alert')

