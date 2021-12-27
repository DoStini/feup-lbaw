@extends('layouts.app')

@section('title', 'Checkout')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8">
            <section id="order-address">
                <h2>Address</h2>
                <div class="accordion" id="addresses-accordion">
                    @foreach ($shopper->addresses as $address)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="address-heading{{$loop->index}}">
                            <button class="accordion-button @if(!$loop->first) collapsed  @endif " type="button" data-bs-toggle="collapse"
                                data-bs-target="#address-panel-collapse{{$loop->index}}" @if($loop->first) aria-expanded="true" @else aria-expanded="false" @endif
                                aria-controls="address-panel-collapse{{$loop->index}}" id="address-button{{$loop->index}}">
                                {{$address->zip_code->zip_code}}, {{$address->street}} {{$address->door}}
                            </button>
                        </h2>
                        <div id="address-panel-collapse{{$loop->index}}" data-bs-parent="#addresses-accordion" class="accordion-collapse collapse @if($loop->first) show @endif"
                            aria-labelledby="address-heading{{$loop->index}}">
                            <div class="accordion-body">
                                <div class="row container justify-content-between">
                                    <div class="col-md-6">
                                        {{$address->street}} {{$address->door}}<br>
                                        {{$address->zip_code->zip_code}}<br>
                                        {{$address->zip_code->county->name}}<br>
                                        {{$address->zip_code->county->district->name}}<br>
                                    </div>
                                    <div class="col-md-4 d-flex justify-content-end align-items-end">
                                        <div class="form-check">
                                             <input class="form-check-input"{{-- onclick="styleChosenAddress({{$loop->index}})"--}} type="radio" name="address-radio" id="address-radio{{$loop->index}}" @if($loop->first) checked @endif>
                                            <label class="form-check-label" for="address-radio">
                                                Use this address
                                            </label>
                                          </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
        <div class="col-md-4 d-flex align-items-center justify-content-start flex-column">
            COUPONS AND STUFF HERE
        </div>
    </div>
</div>

<script defer>
function styleChosenAddress(id) {
    let addressBtn = document.getElementById(`address-button${id}`);
    console.log(addressBtn);
    addressBtn.style.backgroundColor = "#000000";
}
</script>

@endsection
