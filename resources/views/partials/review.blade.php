@foreach ($reviews as $review)
<div class="row mb-4" id="full-review-{{$review->id}}">
    <div class="col-md-2">
        <a href={{route("getUser", ["id" => $review->creator_id])}} class="text-dark text-decoration-none">
            <div class="row">
                <div class="col d-md-flex justify-content-center">
                        @if(File::exists(public_path($review->creator->photo->url)))
                        <img src={{asset($review->creator->photo->url)}} class="profile-pic rounded-circle" height="50" width="50" alt=""
                        loading="lazy" />
                        @else
                        <img src="/img/user.png" class="profile-pic rounded-circle" height="50" alt="" loading="lazy" />
                        @endif
                </div>
            </div>
            <div class="row mt-2">
                <div class="col text-md-center fw-bold">
                    <span>{{$review->creator->name}}</span>
                </div>
            </div>
        </a>
    </div>
    <div class="col">
        <div class="row mb-2">
            <div class="col d-flex flex-md-row flex-column justify-content-between">
                <div>
                    <input type="hidden" id="review-stars-{{$review->id}}" value="{{$review->stars}}">
                    @for ($i = 1; $i <= 5; $i++)
                    <i id="review-stars-{{$review->id}}-{{$i}}" class="bi bi-star{{$review->stars >= $i ? '-fill' : ''}}"></i>
                    @endfor
                </div>
                <span id="score-review-{{$review->id}}" class="text-muted">Shoppers gave this review {{$review->score}} points.</span>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col white-space-pre" id="review-text-{{$review->id}}">{{$review->text}}</div>
        </div>
        <div class="row">
            <div class="col d-flex flex-row align-items-center justify-content-end">
                @if(Auth::check() && (!Auth::user()->is_admin && Auth::user()->id !== $review->creator_id))
                @php
                    $vote = null;

                    if($shopper != null) {
                        $query = $shopper->voted_reviews()->where("review_id", '=', $review->id)->first();
                        if($query) {
                            $vote = $query->details->vote;
                        }
                    }

                    $downvote = $vote === "downvote";
                    $upvote = $vote === "upvote";
                @endphp
                <div class="d-flex fs-5 flex-row">
                    <i id="upvote-review-{{$review->id}}" onclick="vote({{$review->id}}, 'upvote')" data-vote='{{$upvote}}'  class="bi bi-hand-thumbs-up{{$upvote ? '-fill' : ''}} icon-click ms-md-3"></i>
                    <i id="downvote-review-{{$review->id}}" onclick="vote({{$review->id}}, 'downvote')" data-vote='{{$downvote}}' class="bi bi-hand-thumbs-down{{$downvote ? '-fill' : ''}}  icon-click ms-3"></i>
                </div>
                @elseif(Auth::check() && (Auth::user()->is_admin || Auth::user()->id === $review->creator_id))
                <div id="icon-bar-{{$review->id}}" class="d-flex justify-content-end flex-grow-1 flex-md-grow-0 fs-5 flex-row">
                    @if (!Auth::user()->is_admin)
                    <i class="bi bi-pencil-square icon-click ms-3" onclick="showUpdateReview(this, {{$review->id}})"></i>
                    @endif
                    <i class="bi bi-trash icon-click ms-3" onclick="deleteReview({{$review->id}})"></i>
                </div>
                @endif
            </div>
        </div>
        @if($review->photos && !$review->photos->isEmpty())
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
