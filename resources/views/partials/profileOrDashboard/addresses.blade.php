@include('partials.errormodal')

<div id="address-root" class="accordion" id="addresses-accordion">
    @foreach ($shopper->addresses as $address)
    <div id="address-root-{{$address->id}}" class="accordion-item">
        <h2 class="accordion-header" id="address-heading{{$address->id}}">
            <button
                class="accordion-button collapsed"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#address-panel-collapse{{$address->id}}"
                aria-expanded="false"
                aria-controls="address-panel-collapse{{$address->id}}"
                id="address-button{{$address->id}}">
                @if ($address->name != null)
                    {{$address->name}}
                @else
                    {{$address->zip_code->zip_code}}, {{$address->street}} {{$address->door}}
                @endif
            </button>
        </h2>
        <div id="address-panel-collapse{{$address->id}}"
            data-bs-parent="#addresses-accordion"
            class="accordion-collapse collapse"
            aria-labelledby="address-heading{{$address->id}}"
        >
            <div class="accordion-body row container justify-content-between align-items-center">
                <div class="col-6 address-info">
                    {{$address->street}} {{$address->door}}<br>
                    {{$address->zip_code->zip_code}}<br>
                    {{$address->zip_code->county->name}}<br>
                    {{$address->zip_code->county->district->name}}<br>
                </div>
                <div class="col-6 container">
                    <div class="row justify-content-end">
                        <i id="edit-address-{{$address->id}}" class="bi bi-pencil-square col-1 fs-4 px-0 btn edit-address-btn"></i>
                        <i id="remove-address-{{$address->id}}" class="bi bi-x-lg col-1 fs-4 px-0 btn remove-address-btn"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>


<a id="address-form-collapse-trigger" data-bs-toggle="collapse" href="#address-form-collapse" role="button" aria-expanded="false" aria-controls="address-form-collapse">
</a>
<div class="collapse" id="address-form-collapse">
    <div class="mb-5"></div>
    <form id="address-form">
    <div class="card card-body container">
        <div class="row justify-content-between">
            <h4 class="col-6">New Address</h4>
            <i id="close-window" class="col-2 bi bi-x-lg"></i>
        </div>
        <div class="row justify-content-center">
            <div class="mb-3 col-12 col-lg-4">
                <label for="address-name" class="form-label">Address Name</label>
                <input name="name" class="form-control" id="address-name" placeholder="Address Name">
            </div>
            <div class="mb-3 col-12 col-lg-4">
                <label for="street-name" class="form-label">Street</label>
                <input name="street" class="form-control" id="street-name" placeholder="Street Name">
            </div>
            <div class="mb-3 col-12 col-lg-4">
                <label for="door" class="form-label">Door</label>
                <input name="door" class="form-control" id="door" placeholder="1234">
            </div>
            <div class="mb-3 col-12 col-lg-4">
                <label for="zip" class="form-label">
                    Zip Code
                </label>
                <div id="select-target"></div>
                {{-- <select class="address-select form-select" id="zip"> --}}
                </select>
            </div>
            <div class="mb-3 col-12 col-lg-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose a zip code and we will fill this out for you">
                <label for="county" class="form-label">County</label>
                <input name="county" class="form-control" id="county" placeholder="County" disabled readonly>
            </div>
            <div class="mb-3 col-12 col-lg-4" data-bs-toggle="tooltip" data-bs-placement="top" title="Choose a zip code and we will fill this out for you">
                <label for="district" class="form-label">District</label>
                <input name="district" class="form-control" id="district" placeholder="Distict" disabled readonly>
            </div>
                <button class="btn btn-primary mt-3 col-12 col-md-2" type="submit">Submit</button>
            <input id="zip_code_id" name="zip_code_id" style="visibility:collapse"></input>
        </div>
    </div>
    </form>
</div>


<div class="mb-5"></div>
<form>
    <div id="new-address" class="d-flex justify-content-center align-items-center">
        <button class="btn btn-primary">Add a new address</button>
    </div>
    </div>
</form>


<script>
    const userId = {{Auth::user()->id}};
    const addresses = {};

    @foreach ($shopper->addresses as $address)
        addresses[ "{{$address->id}}" ] = {
            zip_code: "{{$address->zip_code->zip_code}}",
            zip_code_id: "{{$address->zip_code->id}}",
            street: "{{addslashes($address->street)}}",
            door: "{{addslashes($address->door)}}",
            county: "{{$address->zip_code->county->name}}",
            district: "{{$address->zip_code->county->district->name}}",
            name: "{{addslashes($address->name)}}"
        }

    @endforeach

</script>
