@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

@include('partials.errormodal')

@if($errors->any())
<script async>
    (async() => {
        while(!window.hasOwnProperty('reportData'))
            await new Promise(resolve => setTimeout(resolve, 100));

        let errors = JSON.parse(`<?php echo($errors->toJson()) ?>`.replace(/\s+/g," "));
        let products = errors.products;

        if(products) {
            delete errors.products;
        }

        reportData("Couldn't checkout the cart", errors, {
            "cart" : "Cart",
            "payment-type" : "Payment Type",
            "address-id" : "Address"
        });

        if(products) {
            const header = document.createElement('h6');
            header.style.fontWeight = "bold";
            header.innerText = "Products without stock:"
            document.getElementById("errorMessageBody").appendChild(header);

            products.forEach((elem) => {
                let product = elem;

                const productImg = product.photos[0];
                const fallBack = "/img/default.jpg";

                const html = `
                <div id="product-${product.id}" class="card mb-5 search-products-item">
                    <img class="card-img-top search-card-top" src="${productImg.url}" onerror="this.src='${fallBack}'">
                    <div class="card-body">
                        <h4 class="card-title" style="height: 2.5em; display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical; overflow: hidden;">${capitalize(product.name)}</h4>
                        <div class="container ps-0 pe-0">
                            <div class="row justify-content-between align-items-center">
                                <h4 class="col mb-0">${product.price} &euro;</h4>
                                <span class="col">Stock: ${product.stock}</span>

                            </div>
                        </div>
                    </div>
                </div>`;

                const element = document.createElement("div");
                element.id = `root-product-${product.id}`;
                element.className = "col-md-6 col-xs-12";
                element.style = "visibility: visible";
                element.innerHTML = html;

                element.querySelector("img").addEventListener('click', () => route(`products/${product.id}`));

                document.getElementById("errorMessageBody").appendChild(element);
            })
            document.getElementById("errorMessageBody").classList.add("row");
        }
    })();
</script>
@endif


<div class="container">
    <form class="row" method="POST" action="{{route('checkout')}}">
        @csrf
        <div class="col-md-8">
            <section id="order-address" class="mb-4">
                <h2 class="mb-4">Address</h2>
                <div class="accordion" id="addresses-accordion">
                    @foreach ($shopper->addresses as $address)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="address-heading{{$loop->index}}">
                            <button
                                class="accordion-button @if(!$loop->first) collapsed @else bg-success text-light selected-address  @endif "
                                type="button" data-bs-toggle="collapse"
                                data-bs-target="#address-panel-collapse{{$loop->index}}" @if($loop->first)
                                aria-expanded="true" @else aria-expanded="false" @endif
                                aria-controls="address-panel-collapse{{$loop->index}}"
                                id="address-button{{$loop->index}}">
                                @if ($address->name != null)
                                    {{$address->name}}
                                @else
                                    {{$address->zip_code->zip_code}}, {{$address->street}} {{$address->door}}
                                @endif
                            </button>
                        </h2>
                        <div id="address-panel-collapse{{$loop->index}}" data-bs-parent="#addresses-accordion"
                            class="accordion-collapse collapse @if($loop->first) show @endif"
                            aria-labelledby="address-heading{{$loop->index}}">
                            <div class="accordion-body row container justify-content-between">
                                <div class="col-md-6">
                                    {{$address->street}} {{$address->door}}<br>
                                    {{$address->zip_code->zip_code}}<br>
                                    {{$address->zip_code->county->name}}<br>
                                    {{$address->zip_code->county->district->name}}<br>
                                </div>
                                <div class="col-md-4 d-flex justify-content-end align-items-end">
                                    <div class="form-check">
                                        <input class="form-check-input" onclick="styleChosenAddress({{$loop->index}})"
                                            type="radio" name="address-id" value="{{$address->id}}"  id="address-radio{{$loop->index}}"
                                            @if($loop->first) checked @endif required>
                                        <label class="form-check-label" for="address-id">
                                            Use this address
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            <section id="order-payment" class="mb-4 d-flex flex-column">
                <h2 class="mb-4">Payment Method</h2>
                <div class="payment-option container mb-4">
                    <div class="row justify-content-between">
                        <div class="col-4 payment-image d-flex justify-content-start align-items-center">
                            <i class="bi-paypal"></i>
                        </div>
                        <div class="col-lg-4 col-6 d-flex justify-content-start align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment-type"
                                    id="payment-radio-paypal" value="paypal" required>
                                <label class="form-check-label" for="payment-type">
                                    Pay with PayPal
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="payment-option container mb-4">
                    <div class="row justify-content-between">
                        <div class="col-4 d-flex justify-content-start align-items-center payment-image">
                            <i class="bi-bank"></i>
                        </div>
                        <div class="col-lg-4 col-6 d-flex justify-content-start align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment-type"
                                    id="payment-radio-bank" value="bank" required>
                                <label class="form-check-label" for="payment-type">
                                    Pay with Bank Transfer
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-start flex-column">
            @include('partials.applyCoupon', ["cartTotal" => $cartTotal])
            @include('partials.cartTotal', ["cartTotal" => $cartTotal])
            <div class="my-4 w-50 d-flex align-items-center justify-content-center">
                <button type="submit" class="w-100 btn btn-primary">Checkout</button>
            </div>
        </div>
    </form>
</div>

<script defer>
    function clearAllChosenAddresses() {
    let buttons = document.querySelectorAll(".selected-address");

    buttons.forEach((btn) => {
        btn.classList.remove("bg-success");
        btn.classList.remove("text-light");
        btn.classList.remove("selected-address");
    })
}

function styleChosenAddress(id) {
    clearAllChosenAddresses();

    let addressBtn = document.getElementById(`address-button${id}`);
    addressBtn.classList.add("bg-success");
    addressBtn.classList.add("text-light");
    addressBtn.classList.add("selected-address");
}
</script>

@endsection
