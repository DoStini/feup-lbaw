@include('partials.errormodal')

<div class="accordion" id="addresses-accordion">
    @foreach ($shopper->addresses as $address)
    <div class="accordion-item">
        <h2 class="accordion-header" id="address-heading{{$loop->index}}">
            <button
                class="accordion-button collapsed"
                type="button" data-bs-toggle="collapse"
                data-bs-target="#address-panel-collapse{{$loop->index}}" 
                aria-expanded="false"
                aria-controls="address-panel-collapse{{$loop->index}}"
                id="address-button{{$loop->index}}">
                {{$address->zip_code->zip_code}}, {{$address->street}} {{$address->door}}
            </button>
        </h2>
        <div id="address-panel-collapse{{$loop->index}}" 
            data-bs-parent="#addresses-accordion"
            class="accordion-collapse collapse"
            aria-labelledby="address-heading{{$loop->index}}"
        >
            <div class="accordion-body row container justify-content-between align-items-center">
                <div class="col-6">
                    {{$address->street}} {{$address->door}}<br>
                    {{$address->zip_code->zip_code}}<br>
                    {{$address->zip_code->county->name}}<br>
                    {{$address->zip_code->county->district->name}}<br>
                </div>
                <i id="edit-address-{{$address->id}}" class="bi bi-pencil-square col-1 fs-4 px-0 btn edit-address-btn"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<a id="address-form-collapse-trigger" data-bs-toggle="collapse" href="#address-form-collapse" role="button" aria-expanded="false" aria-controls="address-form-collapse">
</a>
<div class="collapse" id="address-form-collapse">
    <div class="card card-body container">
        <div class="row">
            <div class="mb-3 col-12 col-lg-8">
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
                <select class="address-select form-select" id="zip">
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
        </div>
    </div>
</div>

{{-- <label for="id_label_single">
    Click this 
</label>
<select class="select-2 js-states form-control" id="id_label_single">
    <option>ola</option>
    <option>mundo</option>
    <option>miro</option>
</select> --}}
