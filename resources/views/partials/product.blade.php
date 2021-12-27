<div class="product container m-0 w-100" data-id={{ $product->id }}>
    <div class="row vw-100">
        <div class="product-images col-8 d-flex justify-content-center align-items-center">
            <div id="carouselExampleControls" class="carousel slide product-slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @php
                        $insertedPhotos = 0;
                    @endphp
                    @if ($product->photos)
                        @foreach ($product->photos as $photo)
                            @if (File::exists(Storage::url($photo->url)))
                                <div class="carousel-item {{$loop->iteration == 1 ? 'active' : '' }}">
                                    <img class="d-block w-100" src={{Storage::url('images/product/default.jpg')}}>
                                </div>
                                @php
                                    $insertedPhotos++;
                                @endphp
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
                  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                  </button>
                @endif
              </div>
        </div>
        <div class="product-info col me-5">
            <div class="my-3">
                <h1>{{$product->name}}</h1>
                <p>
                @for ($i = 1; $i <= 5; $i++)
                      <i class="bi bi-star{{floor($product->avg_stars) >= $i ? '-fill' : (ceil($product->avg_stars) == $i ? '-half' : '')}}"></i>
                @endfor
                </p>
            </div>

            <div class="my-5">
                <h3> {{$product->price}} €</h3>
            </div>

            <div>
                <h4> Available Colors </h4>
                @foreach (json_decode($product->attributes)->color as $color)
                    <span class="dot" style="--color: {{$color}}"></span>
                @endforeach
            </div>

            <!---<h3> Stock </h3>
            <p>{{$product->stock}}</p>
            <h3> Desc </h3>
            <p>{{$product->description}}</p>-->
            
            <div class="quantity-wishlist my-4 justify-content-around align-items-center d-flex">
                @if ($product->stock > 0)
                    <div>
                        <input id="quantity-to-add" type="number" min="1" max={{$product->stock}} value="1">
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
                @if ($product->stock > 0)
                    <button class="btn btn-primary w-100 my-2">Add to Cart</button>
                    <button class="btn btn-success w-100 my-2">In Stock</button>
                @else
                    <button class="btn btn-danger w-100 my-2">Out of Stock</button>
                @endif
                
                <div class="accordion w-100 my-2" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            View More Details
                          </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                          <div class="accordion-body">{{$product->description}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let quantityInputBox = document.getElementById('quantity-to-add');
    let currentPriceContainer = document.getElementById('current-price');
    console.log(quantityInputBox);
    quantityInputBox.addEventListener('change', function() {
        currentPriceContainer.innerText = `${({{$product->price}} * quantityInputBox.value).toFixed(2)} €`;
    })
</script>