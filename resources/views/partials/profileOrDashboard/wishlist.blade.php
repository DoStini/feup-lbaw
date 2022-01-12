
<section id="cart h-100">
    @if($shopper->wishlist->isEmpty())
        <div class="d-flex justify-content-center align-items-center flex-column">
            <h3>You have no items in your cart. Go get some...</h3>
            <form action="/products">
                <button type="submit" class="btn btn-primary">Go to Product List</button>
            </form>
        </div>
    @else
    <h3 class="mx-2 text-center d-block">{{$shopper->user->name}}'s Wishlist</h3>
    <div class="container-fluid px-5 min-vh-75">
        <div class="row">
            <div class="col-12">
                @each('partials.wishlistproduct', $shopper->wishlist , 'wishlistItem')
            </div>
        </div>
    </div>
    @endif
</section>
