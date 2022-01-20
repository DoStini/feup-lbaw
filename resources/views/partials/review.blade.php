
@foreach ($reviews as $review)
    {{$review->stars}};;;;{{$review->text}}
    <br>
@endforeach

<div id="review-links" class="d-flex align-items-center justify-content-end px-md-5">{{$reviews->links()}}</div>
