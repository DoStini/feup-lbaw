
@foreach ($reviews as $review)
    <div class="row mb-4">
        <div class="col-2">
            <div class="row">
                <div class="col d-flex justify-content-center">
                    @if(File::exists(public_path($review->creator->photo->url)))
                    <img src={{asset($review->creator->photo->url)}} class="rounded-circle" height="50" width="50" alt="" loading="lazy" />
                    @else
                    <img src="/img/user.png" class="rounded-circle" height="50" alt="" loading="lazy" />
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col text-center">
                    <span>{{$review->creator->name}}</span>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col">
                    @for ($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{$review->stars >= $i ? '-fill' : ''}}">
                    </i>
                    @endfor
                </div>
            </div>
            <div class="row">
                <div class="col">
                    {{$review->text}}
                </div>
            </div>
            <div class="row">
                <div class="col d-flex flex-md-row flex-column justify-content-end">
                    <span class="text-muted">{{$review->score}} people found this helpful.</span>
                    <div class="d-flex flex-row">
                        <i class="bi bi-hand-thumbs-up ms-md-3"></i>
                        <i class="bi bi-hand-thumbs-down ms-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach

<div id="review-links" class="d-flex align-items-center justify-content-end px-md-5">{{$reviews->links()}}</div>
