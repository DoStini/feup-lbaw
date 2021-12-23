<div class="container">

    <h2 id="results-text" class="mb-5">No results</h2>

    <div class="row justify-content-between align-items-center" role="button" data-bs-toggle="collapse"
        href="#price-input" aria-expanded="false" aria-controls="price-input" aria-controls="price-input">

        <h4 class="col-10">Price</h4>
        <i class="col-2 bi bi-caret-down-fill"></i>
    </div>
    <div class="collapse" id="price-input">
        <label for="price-min">Min</label>
        <input name="price-min" id="price-min" type="number" step="0.01" min="0" class="form-control"
            placeholder="Min Price" aria-label="Min Price">
        <label for="price-max">Max</label>
        <input name="price-max" id="price-max" type="number" step="0.01" min="0" class="form-control col-lg-6 col-md-12"
            placeholder="Max Price" aria-label="Max Price">
    </div>

    <div class="mb-3"></div>

    <div class="row justify-content-between align-items-center" role="button" data-bs-toggle="collapse"
        href="#rate-input" aria-expanded="false" aria-controls="rate-input" aria-controls="rate-input">

        <h4 class="col-10">Rating</h4>
        <i class="col-2 bi bi-caret-down-fill"></i>
    </div>
    <div class="collapse" id="rate-input">
        <label for="rate-min">Min</label>
        <input name="rate-min" id="rate-min" type="number" step="0.01" min="0" class="form-control"
            placeholder="Min Rate" aria-label="Min Rate">
        <label for="rate-max">Max</label>
        <input name="rate-max" id="rate-max" type="number" step="0.01" min="0" class="form-control col-lg-6 col-md-12"
            placeholder="Max Rate" aria-label="Max Rate">
    </div>

    <div class="mb-3"></div>
</div>