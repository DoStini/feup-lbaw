@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<div class="container">
    <form class="row" method="POST" action="">
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
                                {{$address->zip_code->zip_code}}, {{$address->street}} {{$address->door}}
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
                        <div class="col-6 payment-image">
                            <img style="" src="{{asset("img/paypal.png")}}">
                        </div>
                        <div class="col-4 d-flex justify-content-start align-items-center">
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
                        <div class="col-6 payment-image">
                            {{-- <img style="" src="{{asset("img/paypal.png")}}"> --}}
                        </div>
                        <div class="col-4 d-flex justify-content-start align-items-center">
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
            <button type="submit" class="btn btn-primary">Checkout</button>
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
