@foreach ($reviews as $review)
<div class="row mb-4">
    <div class="col-md-2">
        <div class="row">
            <div class="col d-md-flex justify-content-center">
                @if(File::exists(public_path($review->creator->photo->url)))
                <img src={{asset($review->creator->photo->url)}} class="rounded-circle" height="50" width="50" alt=""
                loading="lazy" />
                @else
                <img src="/img/user.png" class="rounded-circle" height="50" alt="" loading="lazy" />
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col text-md-center">
                <span>{{$review->creator->name}}</span>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="row mb-2">
            <div class="col">
                <input type="hidden" id="review-stars-{{$review->id}}" value="{{$review->stars}}">
                @for ($i = 1; $i <= 5; $i++)
                <i id="review-stars-{{$review->id}}-{{$i}}" class="bi bi-star{{$review->stars >= $i ? '-fill' : ''}}"></i>
                @endfor
            </div>
        </div>
        <div class="row mb-2">
            <div class="col" id="review-text-{{$review->id}}">
                {{$review->text}}
            </div>
        </div>
        <div class="row">
            <div class="col d-flex flex-md-row flex-column justify-content-end align-items-md-center">
                <span class="text-muted">{{$review->score}} helpful votes.</span>
                @if(Auth::check() && Auth::user()->id !== $review->creator_id)
                <div class="d-flex fs-5 flex-row">
                    <i class="bi bi-hand-thumbs-up icon-click ms-md-3"></i>
                    <i class="bi bi-hand-thumbs-down icon-click ms-3"></i>
                </div>
                @elseif(Auth::check() && Auth::user()->id === $review->creator_id)
                <div id="icon-bar-{{$review->id}}" class="d-flex fs-5 flex-row">
                    <i class="bi bi-pencil-square icon-click ms-md-3" onclick="showUpdateReview(this, {{$review->id}})"></i>
                </div>
                @endif
            </div>
        </div>
        @if($review->photos && !$review->photos->isEmpty())
        <h4>Review Photos</h4>
        <div class="row">
            <div class="d-none d-md-block">
                @include('partials.reviewPhotos', ['max_photos' => 4, "name" => "big"])
            </div>
            <div class="d-md-none">
                @include('partials.reviewPhotos', ["max_photos" => 2, "name" => "small"])
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach

<div id="review-links" class="d-flex align-items-center justify-content-end px-md-5">{{$reviews->links()}}</div>
