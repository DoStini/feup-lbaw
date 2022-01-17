@extends('layouts.app')

@section('title', 'Cart')

@section('content')

<section id="cart h-100">
    @if($shopper->cart->isEmpty())
    <div class="d-flex justify-content-center align-items-center flex-column">
        <h3>You have no items in your cart. Go get some...</h3>
        <form action="/products">
            <button type="submit" class="btn btn-primary">Go to Product List</button>
        </form>
    </div>
    @else
    <h3 class="mx-2 text-center d-block">{{$shopper->user->name}}'s Cart</h3>
    @php
    $cart = $shopper->cart()->paginate(4);
    @endphp
    <div class="container-fluid px-md-5">
        <div class="row">
            <div class="col-md-8">
                @each('partials.cartproduct', $cart , 'cart_item')
                <div class="d-flex align-items-center justify-content-end">{{$cart->links()}}</div>
            </div>
            <div class="col-md-4 d-flex align-items-center flex-column">
                @include('partials.cartTotal', ["cartTotal" => $cartTotal, "showTotal" => false])
                <div class="my-4 w-50 d-flex align-items-center justify-content-center">
                    <form action="{{route('checkout-page')}}">
                        <button @if(!$stocked) disabled @endif id="proceed-checkout-btn"
                            class="btn btn-primary w-100">Proceed to Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

<script>
    const outOfStock = {};

    window.addEventListener('load', () => {
        document.querySelectorAll("#amount div").forEach((elem) => {
            const selector = createNumberSelector({
                id: `number-selector-${elem.dataset.id}`,
                min: 1,
                value: elem.dataset.amount,
                onBlur: (target, value, prevValue) => {
                    if (value === prevValue) {
                        target.value = value;
                        return;
                    }
                    jsonBodyPost("/api/users/cart/update", { product_id: elem.dataset.id, amount: value})
                        .then(response => {
                            if (response.status === 200) {
                                selector.validInput();
                                console.log(response.data);

                                delete outOfStock[elem.dataset.id];

                                if(response.data.stocked && Object.keys(outOfStock).length === 0) {
                                    document.getElementById("proceed-checkout-btn").disabled = false;
                                } else {
                                    document.getElementById("proceed-checkout-btn").disabled = true;
                                }

                                target.value = value;

                                document.getElementById("order-subtotal").innerText = (parseFloat(response.data.total)).toFixed(2) + " €";
                            }
                        })
                        .catch(error => {
                            if(error.response) {
                                if(error.response.data) {
                                    selector.invalidInput(`Product's stock is ${elem.dataset.stock}`);
                                    document.getElementById("proceed-checkout-btn").disabled = true;

                                    outOfStock[elem.dataset.id] = true;

                                    let errors = "";
                                    for(var key in error.response.data.errors) {
                                        errors = errors.concat(error.response.data.errors[key]);
                                    }
                                    launchSuccessAlert("Couldn't add to the cart: " + error.response.data.message + "<br>" + errors);
                                }
                            }
                        });
                }
            });
            document.getElementById(`cart-number-selector-${elem.dataset.id}`).appendChild(selector);

            console.log(elem.dataset.amount, elem.dataset.stock);

            if(parseInt(elem.dataset.amount) > parseInt(elem.dataset.stock)) {
                selector.invalidInput(`Product's stock is ${elem.dataset.stock}`);
                document.getElementById("proceed-checkout-btn").disabled = true;
            }
        })
    })
</script>

@endsection
