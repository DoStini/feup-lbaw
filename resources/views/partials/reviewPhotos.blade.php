
<div id="review-carousel-{{$name}}-{{$review->id}}" class="col carousel slide w-100" data-bs-ride="carousel">
    <div class="carousel-inner w-100">
        @php
        $insertedReviewPhotos = 0;
        $curPhotos = 0;
        @endphp
        @foreach ($review->photos as $photo)
        @if (File::exists(public_path($photo->url)) || filter_var($photo->url, FILTER_VALIDATE_URL))
        @if($curPhotos === 0)
        <div class="carousel-item w-100 {{$loop->iteration == 1 ? 'active' : '' }}">
            <div class="row">
        @endif

                <div class="col-{{floor(12/$max_photos)}}">
                    <img class="d-block w-100" src={{$photo->url}}>
                </div>
                @php
                $insertedReviewPhotos++;
                $curPhotos++;
                @endphp

        @if($curPhotos === $max_photos)
            </div>
        </div>
        @php
        $curPhotos = 0;
        @endphp
        @endif
        @endif
        @endforeach

        @if ($insertedReviewPhotos < 1)
        <div class="carousel-item active">
            <img class="d-block w-100" src="/img/default.jpg">
        </div>
        @endif

        @if ($curPhotos > 0)
            </div>
        </div>
        @endif
    </div>
    @if ($insertedReviewPhotos > $max_photos)
    <button class="carousel-control-prev" type="button" data-bs-target="#review-carousel-{{$name}}-{{$review->id}}"
        data-bs-slide="prev">
        <span class="carousel-control-prev-icon" style="background-color: rgb(99, 99, 99); border-radius: 25%;"
            aria-hidden="true"></span>
        {{-- <span class="visually-hidden">Previous</span> --}}
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#review-carousel-{{$name}}-{{$review->id}}"
        data-bs-slide="next">
        <span class="carousel-control-next-icon" style="background-color: rgb(99, 99, 99); border-radius: 25%;"
            aria-hidden="true"></span>
        {{-- <span class="visually-hidden">Next</span> --}}
    </button>
    @endif
</div>
